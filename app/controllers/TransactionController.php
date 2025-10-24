<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Helpers\Validator;
use App\Models\Category;
use App\Models\FinancialProfile;
use App\Models\Transaction;
use App\Services\AlertService;
use DateTimeImmutable;

class TransactionController extends Controller
{
    protected array $paymentMethods = ['efectivo', 'tarjeta', 'transferencia', 'otro'];

    public function index(Request $request): void
    {
        if (!$this->auth->check()) {
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        $profileModel = new FinancialProfile($this->db());
        $profile = $profileModel->findByUserId($this->auth->id());

        if (!$profile) {
            $this->response->redirect('/App-Control-Gastos/public/perfil/configuracion-inicial');
            return;
        }

        $filters = [
            'month' => $request->input('month') ?: date('Y-m'),
            'type' => $request->input('type'),
            'category' => $request->input('category'),
            'payment_method' => $request->input('payment_method'),
        ];

        $categoryModel = new Category($this->db());
        $transactionModel = new Transaction($this->db());
        [$start, $end] = $this->resolveMonthRange($filters['month']);

        $transactions = $transactionModel->all($this->auth->id(), $filters);
        $summary = $this->buildSummary($transactionModel, $profile, $start, $end);
        $distribution = $transactionModel->totalsByCategory($this->auth->id(), $start, $end);
        $methods = $transactionModel->totalsByPaymentMethod($this->auth->id(), $start, $end);

        $this->render('transactions/index', [
            'title' => 'Movimientos financieros',
            'profile' => $profile,
            'transactions' => $transactions,
            'filters' => $filters,
            'summary' => $summary,
            'distribution' => $distribution,
            'methods' => $methods,
            'expenseCategories' => $categoryModel->forUser($this->auth->id(), 'expense'),
            'incomeCategories' => $categoryModel->forUser($this->auth->id(), 'income'),
            'paymentMethods' => $this->paymentMethods,
            'recent' => $transactionModel->recent($this->auth->id(), 5),
            'old' => $this->session->pullOld(),
        ]);
    }

    public function store(Request $request): void
    {
        if (!$this->auth->check()) {
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        if (!$this->validateToken($request)) {
            $this->session->flash('error', 'Token invalido.');
            $this->response->redirect('/App-Control-Gastos/public/transacciones');
            return;
        }

        $input = $request->only([
            'type',
            'category',
            'category_new',
            'amount',
            'payment_method',
            'happened_on',
            'description',
        ]);

        $validator = Validator::make($input)
            ->required('type', 'Selecciona si es ingreso o gasto.')
            ->in('type', ['income', 'expense'], 'Tipo de transaccion no valido.')
            ->required('amount', 'Ingresa el monto.')
            ->numeric('amount', 'El monto debe ser numerico.')
            ->minValue('amount', 0.01, 'El monto debe ser mayor a cero.')
            ->required('payment_method', 'Selecciona un metodo de pago.')
            ->in('payment_method', $this->paymentMethods, 'Metodo de pago no valido.')
            ->required('happened_on', 'Selecciona la fecha.')
            ->maxLength('description', 255, 'La descripcion es demasiado larga.');

        $categoryName = '';
        if (!empty($input['category_new'])) {
            $categoryName = trim($input['category_new']);
        } elseif (!empty($input['category'])) {
            $categoryName = trim($input['category']);
        }

        if ($categoryName === '') {
            $validator->required('category', 'Selecciona o crea una categoria.');
        }

        if ($validator->fails()) {
            $this->session->flash('error', 'Revisa la informacion del movimiento.');
            $this->session->flashInput($input);
            $this->response->redirect('/App-Control-Gastos/public/transacciones');
            return;
        }

        $categoryModel = new Category($this->db());
        $transactionModel = new Transaction($this->db());
        $profileModel = new FinancialProfile($this->db());
        $profile = $profileModel->findByUserId($this->auth->id());

        if (!$profile) {
            $this->session->flash('error', 'Configura tu perfil financiero antes de registrar movimientos.');
            $this->response->redirect('/App-Control-Gastos/public/perfil/configuracion-inicial');
            return;
        }

        $categoryType = $input['type'] === 'income' ? 'income' : 'expense';
        $existingCategory = $categoryModel->findByName($this->auth->id(), $categoryName, $categoryType);
        if (!$existingCategory) {
            $categoryModel->create($this->auth->id(), $categoryName, $categoryType);
        }

        $transactionModel->create($this->auth->id(), [
            'type' => $input['type'],
            'category' => $categoryName,
            'amount' => round((float) $input['amount'], 2),
            'payment_method' => $input['payment_method'],
            'happened_on' => $input['happened_on'],
            'description' => trim($input['description'] ?? '') ?: null,
        ]);

        [$start, $end] = $this->resolveMonthRange(date('Y-m', strtotime($input['happened_on'])) ?: date('Y-m'));
        $expenseTotal = $transactionModel->totalsByType($this->auth->id(), 'expense', $start, $end);
        $baseIncome = (float) $profile['monthly_income'] + (float) $profile['extra_income'];
        $additionalIncome = $transactionModel->totalsByType($this->auth->id(), 'income', $start, $end);
        $available = ($baseIncome + $additionalIncome) - $expenseTotal;

        /** @var AlertService $alertService */
        $alertService = $this->container->get(AlertService::class);
        $alertService->handleLimitStatus(
            $this->auth->id(),
            (float) $profile['spending_limit_amount'],
            $expenseTotal,
            $available,
            $profile['currency']
        );
        $alertService->handleInactivity(
            $this->auth->id(),
            new DateTimeImmutable($input['happened_on'])
        );

        if ($input['type'] === 'expense' && $expenseTotal > (float) $profile['spending_limit_amount']) {
            $this->session->flash('error', 'Atencion: superaste tu limite mensual de gastos.');
            $this->session->flash('info', 'Disponibilidad actual: ' . number_format($available, 2) . ' ' . $profile['currency']);
        } else {
            $this->session->flash('success', 'Movimiento registrado correctamente.');
        }

        $this->response->redirect('/App-Control-Gastos/public/transacciones');
    }

    public function delete(Request $request): void
    {
        if (!$this->auth->check()) {
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        if (!$this->validateToken($request)) {
            $this->session->flash('error', 'Token invalido.');
            $this->response->redirect('/App-Control-Gastos/public/transacciones');
            return;
        }

        $id = (int) $request->input('transaction_id');
        if ($id <= 0) {
            $this->session->flash('error', 'Movimiento no valido.');
            $this->response->redirect('/App-Control-Gastos/public/transacciones');
            return;
        }

        $transactionModel = new Transaction($this->db());
        $transactionModel->delete($this->auth->id(), $id);

        $this->session->flash('info', 'Movimiento eliminado correctamente.');
        $this->response->redirect('/App-Control-Gastos/public/transacciones');
    }

    protected function buildSummary(Transaction $transactionModel, array $profile, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $baseIncome = (float) $profile['monthly_income'] + (float) $profile['extra_income'];
        $additionalIncome = $transactionModel->totalsByType($this->auth->id(), 'income', $start, $end);
        $totalExpenses = $transactionModel->totalsByType($this->auth->id(), 'expense', $start, $end);

        $available = ($baseIncome + $additionalIncome) - $totalExpenses;
        $limit = (float) $profile['spending_limit_amount'];
        $limitUsage = $limit > 0 ? min(100, round(($totalExpenses / $limit) * 100, 2)) : 0;

        return [
            'base_income' => $baseIncome,
            'additional_income' => $additionalIncome,
            'total_income' => $baseIncome + $additionalIncome,
            'total_expenses' => $totalExpenses,
            'available' => $available,
            'limit' => $limit,
            'limit_usage' => $limitUsage,
            'over_limit' => $totalExpenses > $limit,
        ];
    }

    protected function resolveMonthRange(string $month): array
    {
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        $start = DateTimeImmutable::createFromFormat('Y-m-d', $month . '-01');
        if (!$start) {
            $start = new DateTimeImmutable('first day of this month');
        }

        $end = $start->modify('last day of this month');

        return [$start, $end];
    }

    protected function validateToken(Request $request): bool
    {
        $token = $request->input('_token');
        return hash_equals($this->session->token(), (string) $token);
    }
}
