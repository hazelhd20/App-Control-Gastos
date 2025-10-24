<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\FinancialProfile;
use App\Models\Transaction;
use App\Services\AlertService;
use App\Services\ReportService;
use DateTimeImmutable;

class AlertController extends Controller
{
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

        [$start, $end] = $this->currentMonthRange();
        $transactionModel = new Transaction($this->db());
        $recent = $transactionModel->recent($this->auth->id(), 1);
        $lastMovement = null;
        if (!empty($recent)) {
            $lastMovement = new DateTimeImmutable($recent[0]['happened_on']);
        }

        $baseIncome = (float) $profile['monthly_income'] + (float) $profile['extra_income'];
        $registeredIncome = $transactionModel->totalsByType($this->auth->id(), 'income', $start, $end);
        $expenses = $transactionModel->totalsByType($this->auth->id(), 'expense', $start, $end);
        $available = ($baseIncome + $registeredIncome) - $expenses;

        /** @var AlertService $alertService */
        $alertService = $this->container->get(AlertService::class);
        $alertService->handleLimitStatus(
            $this->auth->id(),
            (float) $profile['spending_limit_amount'],
            $expenses,
            $available,
            $profile['currency'] ?? 'MXN'
        );
        $alertService->handleInactivity($this->auth->id(), $lastMovement);
        $alertService->handleGoalReminder($this->auth->id(), $profile, $start, $end);
        $alerts = $alertService->getActive($this->auth->id());

        /** @var ReportService $reportService */
        $reportService = $this->container->get(ReportService::class);
        $trend = $reportService->monthlyTrend($this->auth->id(), $end, 4);

        $this->render('alerts/index', [
            'title' => 'Alertas y recordatorios',
            'alerts' => $alerts,
            'profile' => $profile,
            'trend' => $trend,
        ]);
    }

    public function markSeen(Request $request): void
    {
        if (!$this->auth->check()) {
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        if (!$this->validateToken($request)) {
            $this->session->flash('error', 'Token invalido.');
            $this->response->redirect('/App-Control-Gastos/public/alertas');
            return;
        }

        $alertId = (int) $request->input('alert_id');
        if ($alertId <= 0) {
            $this->session->flash('error', 'Alerta no valida.');
            $this->response->redirect('/App-Control-Gastos/public/alertas');
            return;
        }

        /** @var AlertService $alertService */
        $alertService = $this->container->get(AlertService::class);
        $alertService->markSeen($this->auth->id(), $alertId);

        $this->session->flash('info', 'Alerta marcada como atendida.');
        $this->response->redirect('/App-Control-Gastos/public/alertas');
    }

    protected function currentMonthRange(): array
    {
        $start = new DateTimeImmutable('first day of this month');
        $end = new DateTimeImmutable('last day of this month');

        return [$start, $end];
    }

    protected function validateToken(Request $request): bool
    {
        $token = $request->input('_token');
        return hash_equals($this->session->token(), (string) $token);
    }
}
