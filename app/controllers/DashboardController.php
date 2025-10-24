<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\FinancialProfile;
use App\Models\Transaction;
use App\Services\ReportService;
use App\Services\AlertService;
use DateTimeImmutable;

class DashboardController extends Controller
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

        [$monthStart, $monthEnd] = $this->currentMonthRange();
        $transactionModel = new Transaction($this->db());

        $baseIncome = (float) $profile['monthly_income'] + (float) $profile['extra_income'];
        $registeredIncome = $transactionModel->totalsByType($this->auth->id(), 'income', $monthStart, $monthEnd);
        $expenses = $transactionModel->totalsByType($this->auth->id(), 'expense', $monthStart, $monthEnd);
        $available = ($baseIncome + $registeredIncome) - $expenses;

        $limit = (float) $profile['spending_limit_amount'];
        $limitUsage = $limit > 0 ? min(100, round(($expenses / $limit) * 100, 1)) : 0.0;

        /** @var ReportService $reportService */
        $reportService = $this->container->get(ReportService::class);
        $trend = $reportService->monthlyTrend($this->auth->id(), $monthEnd, 4);
        $categoryBreakdown = $reportService->categoryBreakdown($this->auth->id(), $monthStart, $monthEnd)['expense'] ?? [];
        $topCategories = array_slice($categoryBreakdown, 0, 3);
        $paymentBreakdown = $transactionModel->totalsByPaymentMethod($this->auth->id(), $monthStart, $monthEnd);

        $recent = $transactionModel->recent($this->auth->id(), 5);
        $lastMovementDate = $recent[0]['happened_on'] ?? null;
        $lastMovement = null;
        $daysSinceLastMovement = null;
        if ($lastMovementDate) {
            $lastMovement = new DateTimeImmutable($lastMovementDate);
            $daysSinceLastMovement = (int) $lastMovement->diff(new DateTimeImmutable('today'))->format('%a');
        }

        /** @var AlertService $alertService */
        $alertService = $this->container->get(AlertService::class);
        $alertService->handleLimitStatus(
            $this->auth->id(),
            $limit,
            $expenses,
            $available,
            $profile['currency'] ?? 'MXN'
        );
        $alertService->handleInactivity($this->auth->id(), $lastMovement);
        $alertService->handleGoalReminder($this->auth->id(), $profile, $monthStart, $monthEnd);
        $alerts = $alertService->getActive($this->auth->id());

        $this->render('dashboard/index', [
            'title' => 'Panel de control',
            'profile' => $profile,
            'summary' => [
                'base_income' => $baseIncome,
                'registered_income' => $registeredIncome,
                'expenses' => $expenses,
                'available' => $available,
                'limit' => $limit,
                'limit_usage' => $limitUsage,
                'over_limit' => $expenses > $limit,
            ],
            'trend' => $trend,
            'topCategories' => $topCategories,
            'paymentBreakdown' => $paymentBreakdown,
            'recent' => $recent,
            'daysSinceLastMovement' => $daysSinceLastMovement,
            'alerts' => $alerts,
        ]);
    }

    protected function currentMonthRange(): array
    {
        $start = new DateTimeImmutable('first day of this month');
        $end = new DateTimeImmutable('last day of this month');

        return [$start, $end];
    }
}
