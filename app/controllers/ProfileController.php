<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Helpers\Validator;
use App\Models\FinancialProfile;
use App\Models\User;

class ProfileController extends Controller
{
    protected array $currencies = ['MXN', 'USD', 'EUR'];
    protected array $spendingMediaOptions = ['efectivo', 'tarjeta', 'transferencia', 'otros'];

    public function show(Request $request): void
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

        $user = $this->auth->user();

        $this->render('profile/index', [
            'title' => 'Perfil financiero',
            'user' => $user,
            'profile' => $profile,
            'summary' => $this->buildSummary($profile),
            'mediaOptions' => $this->spendingMediaOptions,
            'currencies' => $this->currencies,
            'old' => $this->session->pullOld(),
        ]);
    }

    public function update(Request $request): void
    {
        if (!$this->auth->check()) {
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        if (!$this->validateToken($request)) {
            $this->session->flash('error', 'Token invalido.');
            $this->response->redirect('/App-Control-Gastos/public/perfil');
            return;
        }

        $input = $request->all();
        $input['spending_media'] = $request->inputArray('spending_media');

        $validator = $this->profileValidator($input);
        if ($validator->fails()) {
            $this->session->flash('error', 'Revisa la informacion proporcionada.');
            $this->session->flashInput($input);
            $this->response->redirect('/App-Control-Gastos/public/perfil');
            return;
        }

        $userModel = new User($this->db());
        $normalizedEmail = strtolower(trim($input['email']));

        if ($userModel->emailExists($normalizedEmail, $this->auth->id())) {
            $this->session->flash('error', 'El correo ya esta en uso por otro usuario.');
            $this->session->flashInput($input);
            $this->response->redirect('/App-Control-Gastos/public/perfil');
            return;
        }

        $profileModel = new FinancialProfile($this->db());

        $payload = $this->prepareProfilePayload($input);
        $profileModel->update($this->auth->id(), $payload);

        $userModel->updateProfile($this->auth->id(), [
            'name' => trim($input['name']),
            'phone' => $input['phone'],
            'occupation' => $input['occupation'],
            'email' => $normalizedEmail,
        ]);

        $this->session->flash('success', 'Perfil actualizado correctamente.');
        $this->response->redirect('/App-Control-Gastos/public/perfil');
    }

    public function showInitialSetup(Request $request): void
    {
        if (!$this->auth->check()) {
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        $profileModel = new FinancialProfile($this->db());
        if ($profileModel->existsForUser($this->auth->id())) {
            $this->response->redirect('/App-Control-Gastos/public/perfil');
            return;
        }

        $this->render('profile/setup', [
            'title' => 'Configura tu perfil financiero',
            'currencies' => $this->currencies,
            'mediaOptions' => $this->spendingMediaOptions,
            'old' => $this->session->pullOld(),
        ]);
    }

    public function storeInitialSetup(Request $request): void
    {
        if (!$this->auth->check()) {
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        if (!$this->validateToken($request)) {
            $this->session->flash('error', 'Token invalido.');
            $this->response->redirect('/App-Control-Gastos/public/perfil/configuracion-inicial');
            return;
        }

        $profileModel = new FinancialProfile($this->db());
        if ($profileModel->existsForUser($this->auth->id())) {
            $this->session->flash('info', 'Ya tienes un perfil configurado.');
            $this->response->redirect('/App-Control-Gastos/public/perfil');
            return;
        }

        $input = $request->all();
        $input['spending_media'] = $request->inputArray('spending_media');

        $validator = $this->profileValidator($input, false);

        if ($validator->fails()) {
            $this->session->flash('error', 'Por favor corrige la informacion para continuar.');
            $this->session->flashInput($input);
            $this->response->redirect('/App-Control-Gastos/public/perfil/configuracion-inicial');
            return;
        }

        $payload = $this->prepareProfilePayload($input);
        $profileModel->create($this->auth->id(), $payload);

        $this->session->flash('success', 'Perfil inicial configurado con exito.');
        $this->response->redirect('/App-Control-Gastos/public');
    }

    protected function profileValidator(array $data, bool $updating = true): Validator
    {
        $validator = Validator::make($data)
            ->required('monthly_income', 'Ingresa tu ingreso mensual.')
            ->numeric('monthly_income')
            ->minValue('monthly_income', 0, 'El ingreso mensual no puede ser negativo.')
            ->numeric('extra_income', 'El ingreso adicional debe ser numerico.')
            ->minValue('extra_income', 0, 'El ingreso adicional no puede ser negativo.')
            ->required('start_date', 'Selecciona una fecha de inicio.')
            ->required('currency', 'Selecciona una moneda.')
            ->in('currency', $this->currencies, 'Moneda no valida.')
            ->arrayNotEmpty('spending_media', 'Selecciona al menos un medio de gasto.')
            ->required('goal_type', 'Selecciona un objetivo financiero.')
            ->in('goal_type', ['save', 'debt', 'control', 'other'], 'Objetivo invalido.')
            ->required('spending_limit_mode', 'Selecciona como definir el limite.')
            ->in('spending_limit_mode', ['manual', 'auto']);

        if ($updating) {
            $validator
                ->required('name', 'Ingresa tu nombre.')
                ->required('phone', 'Ingresa tu telefono.')
                ->required('occupation', 'Ingresa tu ocupacion.')
                ->required('email', 'Ingresa tu correo.')
                ->email('email');
        }

        if (($data['goal_type'] ?? '') === 'save') {
            $validator
                ->required('goal_meta_amount', 'Ingresa tu meta de ahorro.')
                ->numeric('goal_meta_amount')
                ->minValue('goal_meta_amount', 1, 'La meta debe ser mayor a 1.')
                ->required('goal_meta_months', 'Ingresa el tiempo estimado para la meta.')
                ->integer('goal_meta_months')
                ->minValue('goal_meta_months', 1, 'Los meses deben ser mayores a 0.');
        }

        if (($data['goal_type'] ?? '') === 'debt') {
            $validator
                ->required('debt_total_amount', 'Ingresa el monto total de tu deuda.')
                ->numeric('debt_total_amount')
                ->minValue('debt_total_amount', 1, 'El monto de la deuda debe ser mayor a 1.');
        }

        if (($data['goal_type'] ?? '') === 'other') {
            $validator->required('goal_description', 'Describe tu objetivo.');
        }

        if (($data['spending_limit_mode'] ?? '') === 'manual') {
            $validator
                ->required('spending_limit_amount', 'Ingresa tu limite mensual.')
                ->numeric('spending_limit_amount')
                ->minValue('spending_limit_amount', 1, 'El limite debe ser mayor a 1.');
        } else {
            $validator->numeric('auto_limit_ratio')->minValue('auto_limit_ratio', 0.1, 'El porcentaje debe ser mayor a 0.1');
        }

        return $validator;
    }

    protected function prepareProfilePayload(array $data): array
    {
        $monthlyIncome = (float) ($data['monthly_income'] ?? 0);
        $extraIncome = (float) ($data['extra_income'] ?? 0);
        $limitMode = $data['spending_limit_mode'] ?? 'manual';
        $ratio = isset($data['auto_limit_ratio']) ? (float) $data['auto_limit_ratio'] : 0.7;

        if ($limitMode === 'auto') {
            $ratio = $this->normalizeRatio($ratio);
            $spendingLimit = round(($monthlyIncome + $extraIncome) * $ratio, 2);
        } else {
            $spendingLimit = round((float) $data['spending_limit_amount'], 2);
        }

        $goalDescription = isset($data['goal_description']) ? trim((string) $data['goal_description']) : null;
        if ($goalDescription === '') {
            $goalDescription = null;
        }

        $goalMetaAmount = $this->nullableFloat($data['goal_meta_amount'] ?? null);
        $goalMetaMonths = $this->nullableInt($data['goal_meta_months'] ?? null);
        $debtTotal = $this->nullableFloat($data['debt_total_amount'] ?? null);

        $payload = [
            'monthly_income' => round($monthlyIncome, 2),
            'extra_income' => round($extraIncome, 2),
            'start_date' => $data['start_date'],
            'currency' => $data['currency'],
            'spending_media' => $this->sanitizeSpendingMedia($data['spending_media'] ?? []),
            'goal_type' => $data['goal_type'],
            'goal_description' => $goalDescription,
            'goal_meta_amount' => $goalMetaAmount,
            'goal_meta_months' => $goalMetaMonths,
            'debt_total_amount' => $debtTotal,
            'debt_plan' => null,
            'spending_limit_mode' => $limitMode,
            'spending_limit_amount' => $spendingLimit,
            'auto_limit_ratio' => $limitMode === 'auto' ? $ratio : null,
        ];

        if ($data['goal_type'] === 'save') {
            $payload['goal_description'] ??= 'Construir ahorro';
        }

        if ($data['goal_type'] === 'debt' && $debtTotal) {
            $payload['debt_plan'] = $this->buildDebtPlan(
                $debtTotal,
                $monthlyIncome + $extraIncome
            );
        }

        return $payload;
    }

    protected function sanitizeSpendingMedia(array $media): array
    {
        $media = array_map('strtolower', $media);
        $media = array_intersect($media, $this->spendingMediaOptions);

        return array_values(array_unique($media));
    }

    protected function buildDebtPlan(float $debtTotal, float $totalIncome): array
    {
        $available = max($totalIncome * 0.25, 500);
        $months = max((int) ceil($debtTotal / max($available, 1)), 1);
        $monthlyPayment = round($debtTotal / $months, 2);

        return [
            'recommended_months' => $months,
            'monthly_payment' => $monthlyPayment,
            'available_budget' => round($available, 2),
            'tips' => [
                'Prioriza el pago minimo sugerido cada mes.',
                'Recorta gastos discrecionales hasta completar el plan.',
                'Usa cualquier ingreso extra para adelantar pagos.',
            ],
        ];
    }

    protected function buildSummary(array $profile): array
    {
        $totalIncome = (float) $profile['monthly_income'] + (float) $profile['extra_income'];
        $media = $profile['spending_media'] ?? [];
        if (!is_array($media)) {
            $decoded = json_decode((string) $media, true);
            $media = is_array($decoded) ? $decoded : [];
        }

        return [
            'income_total' => $totalIncome,
            'limit' => (float) $profile['spending_limit_amount'],
            'media' => $media,
            'goal' => [
                'type' => $profile['goal_type'],
                'description' => $profile['goal_description'],
                'meta_amount' => $profile['goal_meta_amount'],
                'meta_months' => $profile['goal_meta_months'],
                'debt_plan' => $profile['debt_plan'] ?? null,
            ],
            'currency' => $profile['currency'],
        ];
    }

    protected function normalizeRatio(float $ratio): float
    {
        if ($ratio <= 0) {
            return 0.7;
        }

        if ($ratio > 1) {
            return round($ratio / 100, 2);
        }

        return min(max($ratio, 0.3), 0.9);
    }

    protected function nullableFloat(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return round((float) $value, 2);
    }

    protected function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    protected function validateToken(Request $request): bool
    {
        $token = $request->input('_token');
        return hash_equals($this->session->token(), (string) $token);
    }
}
