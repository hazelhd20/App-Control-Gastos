<?php

namespace App\Services;

use App\Core\Database;
use App\Models\Transaction;
use DateInterval;
use DateTimeImmutable;

class ReportService
{
    protected Transaction $transactions;

    public function __construct(Database $database)
    {
        $this->transactions = new Transaction($database);
    }

    public function overview(int $userId, DateTimeImmutable $start, DateTimeImmutable $end, array $profile): array
    {
        $baseIncome = (float) ($profile['monthly_income'] ?? 0) + (float) ($profile['extra_income'] ?? 0);
        $registeredIncome = $this->transactions->totalsByType($userId, 'income', $start, $end);
        $expenses = $this->transactions->totalsByType($userId, 'expense', $start, $end);
        $net = ($baseIncome + $registeredIncome) - $expenses;

        return [
            'base_income' => $baseIncome,
            'registered_income' => $registeredIncome,
            'expenses' => $expenses,
            'net' => $net,
            'balance' => $baseIncome + $registeredIncome - $expenses,
        ];
    }

    public function categoryBreakdown(int $userId, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $raw = $this->transactions->totalsByCategory($userId, $start, $end);
        $result = [
            'income' => [],
            'expense' => [],
        ];

        foreach ($raw as $item) {
            $type = $item['type'] === 'income' ? 'income' : 'expense';
            $result[$type][] = [
                'category' => $item['category'],
                'total' => (float) $item['total'],
            ];
        }

        return $result;
    }

    public function paymentMethodBreakdown(int $userId, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $rows = $this->transactions->totalsByPaymentMethod($userId, $start, $end);

        return array_map(static function (array $item): array {
            return [
                'payment_method' => $item['payment_method'],
                'total' => (float) $item['total'],
            ];
        }, $rows);
    }

    public function monthlyTrend(int $userId, DateTimeImmutable $end, int $months = 6): array
    {
        $start = $end->modify('-' . ($months - 1) . ' months')->modify('first day of this month');
        $data = $this->transactions->monthlyEvolution($userId, $start, $end);

        $cursor = $start;
        $series = [];

        for ($i = 0; $i < $months; $i++) {
            $key = $cursor->format('Y-m');
            $series[$key] = [
                'income' => 0.0,
                'expense' => 0.0,
            ];
            $cursor = $cursor->add(new DateInterval('P1M'));
        }

        foreach ($data as $row) {
            $key = $row['month'];
            if (!isset($series[$key])) {
                $series[$key] = ['income' => 0.0, 'expense' => 0.0];
            }

            $series[$key]['income'] = (float) $row['total_income'];
            $series[$key]['expense'] = (float) $row['total_expense'];
        }

        return [
            'labels' => array_keys($series),
            'income' => array_map(static fn ($item) => $item['income'], $series),
            'expense' => array_map(static fn ($item) => $item['expense'], $series),
        ];
    }

    public function transactionsForExport(int $userId, DateTimeImmutable $start, DateTimeImmutable $end, array $filters = []): array
    {
        $filters = array_merge($filters, [
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
        ]);

        return $this->transactions->all($userId, $filters);
    }
}
