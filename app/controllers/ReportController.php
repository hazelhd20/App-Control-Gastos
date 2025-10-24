<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\FinancialProfile;
use App\Models\Transaction;
use App\Services\ReportService;
use DateTimeImmutable;

class ReportController extends Controller
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

        [$start, $end] = $this->resolveRange($request->input('from'), $request->input('to'));
        $filters = [
            'type' => $request->input('type'),
            'category' => $request->input('category'),
            'payment_method' => $request->input('payment_method'),
        ];

        /** @var ReportService $reportService */
        $reportService = $this->container->get(ReportService::class);

        $overview = $reportService->overview($this->auth->id(), $start, $end, $profile);
        $categoryBreakdown = $reportService->categoryBreakdown($this->auth->id(), $start, $end);
        $methodBreakdown = $reportService->paymentMethodBreakdown($this->auth->id(), $start, $end);
        $trend = $reportService->monthlyTrend($this->auth->id(), $end, 6);

        $transactionModel = new Transaction($this->db());
        $tableData = $transactionModel->all($this->auth->id(), array_merge($filters, [
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
        ]));

        $this->render('reports/index', [
            'title' => 'Reportes y estadisticas',
            'profile' => $profile,
            'overview' => $overview,
            'categoryBreakdown' => $categoryBreakdown,
            'methodBreakdown' => $methodBreakdown,
            'trend' => $trend,
            'filters' => array_merge($filters, [
                'from' => $start->format('Y-m-d'),
                'to' => $end->format('Y-m-d'),
            ]),
            'rows' => $tableData,
        ]);
    }

    public function export(Request $request): void
    {
        if (!$this->auth->check()) {
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        [$start, $end] = $this->resolveRange($request->input('from'), $request->input('to'));
        $format = strtolower($request->input('format', 'csv'));
        $filters = [
            'type' => $request->input('type'),
            'category' => $request->input('category'),
            'payment_method' => $request->input('payment_method'),
        ];

        /** @var ReportService $reportService */
        $reportService = $this->container->get(ReportService::class);
        $data = $reportService->transactionsForExport($this->auth->id(), $start, $end, $filters);

        if ($format === 'xlsx' || $format === 'xls') {
            $this->exportAsExcel($data, $start, $end);
            return;
        }

        $this->exportAsCsv($data, $start, $end);
    }

    protected function resolveRange(?string $from, ?string $to): array
    {
        $today = new DateTimeImmutable('today');
        $defaultStart = $today->modify('first day of this month');
        $defaultEnd = $today->modify('last day of this month');

        $start = $this->parseDate($from) ?? $defaultStart;
        $end = $this->parseDate($to) ?? $defaultEnd;

        if ($end < $start) {
            [$start, $end] = [$end, $start];
        }

        return [$start, $end];
    }

    protected function parseDate(?string $date): ?DateTimeImmutable
    {
        if (!$date) {
            return null;
        }

        $parsed = DateTimeImmutable::createFromFormat('Y-m-d', $date);
        return $parsed ?: null;
    }

    protected function exportAsCsv(array $rows, DateTimeImmutable $start, DateTimeImmutable $end): void
    {
        $filename = 'reporte_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Fecha', 'Tipo', 'Categoria', 'Descripcion', 'Metodo', 'Monto']);

        foreach ($rows as $row) {
            fputcsv($output, [
                $row['happened_on'],
                strtoupper($row['type']),
                $row['category'],
                $row['description'],
                $row['payment_method'],
                number_format((float) $row['amount'], 2, '.', ''),
            ]);
        }

        fclose($output);
        exit;
    }

    protected function exportAsExcel(array $rows, DateTimeImmutable $start, DateTimeImmutable $end): void
    {
        $filename = 'reporte_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.xls';
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        echo "<table border='1'>";
        echo '<tr><th>Fecha</th><th>Tipo</th><th>Categoria</th><th>Descripcion</th><th>Metodo</th><th>Monto</th></tr>';
        foreach ($rows as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['happened_on']) . '</td>';
            echo '<td>' . htmlspecialchars(strtoupper($row['type'])) . '</td>';
            echo '<td>' . htmlspecialchars($row['category']) . '</td>';
            echo '<td>' . htmlspecialchars($row['description']) . '</td>';
            echo '<td>' . htmlspecialchars($row['payment_method']) . '</td>';
            echo '<td>' . number_format((float) $row['amount'], 2, '.', '') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        exit;
    }
}
