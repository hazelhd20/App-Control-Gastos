<?php

namespace App\Services;

use App\Core\Database;
use App\Models\Transaction;
use DateInterval;
use DateTimeImmutable;
use PDO;

class AlertService
{
    protected PDO $db;
    protected Transaction $transactions;

    public function __construct(Database $database)
    {
        $this->db = $database->pdo();
        $this->transactions = new Transaction($database);
    }

    public function handleLimitStatus(int $userId, float $limit, float $expenses, float $available, string $currency): void
    {
        if ($limit <= 0) {
            $this->clearType($userId, 'limit');
            return;
        }

        $usage = $expenses / $limit;

        if ($expenses >= $limit) {
            $message = 'Superaste tu limite mensual de gastos.';
            $payload = [
                'limit' => $limit,
                'expenses' => $expenses,
                'available' => $available,
                'currency' => $currency,
            ];
            $this->upsert($userId, 'limit', 'danger', $message, $payload);
            return;
        }

        if ($usage >= 0.9) {
            $remaining = $limit - $expenses;
            $message = 'Estas por alcanzar tu limite mensual.';
            $payload = [
                'limit' => $limit,
                'expenses' => $expenses,
                'remaining' => $remaining,
                'currency' => $currency,
            ];
            $this->upsert($userId, 'limit', 'warning', $message, $payload);
            return;
        }

        $this->clearType($userId, 'limit');
    }

    public function handleInactivity(int $userId, ?DateTimeImmutable $lastMovement, int $thresholdDays = 7): void
    {
        if (!$lastMovement) {
            $message = 'Aun no registras movimientos. Empieza registrando tus ingresos y gastos.';
            $this->upsert($userId, 'inactivity', 'info', $message);
            return;
        }

        $today = new DateTimeImmutable('today');
        $diff = $lastMovement->diff($today)->days;

        if ($diff >= $thresholdDays) {
            $message = "Han pasado {$diff} dias sin movimientos nuevos. Registra tus operaciones para mantener el control.";
            $payload = [
                'days_without_activity' => $diff,
                'last_movement' => $lastMovement->format('Y-m-d'),
            ];
            $this->upsert($userId, 'inactivity', 'warning', $message, $payload);
            return;
        }

        $this->clearType($userId, 'inactivity');
    }

    public function handleGoalReminder(int $userId, array $profile, DateTimeImmutable $start, DateTimeImmutable $end): void
    {
        $goalType = $profile['goal_type'] ?? null;

        if ($goalType === 'save') {
            $target = (float) ($profile['goal_meta_amount'] ?? 0);
            if ($target <= 0) {
                $this->clearType($userId, 'goal');
                return;
            }

            $categoryData = $this->transactions->totalsByCategory($userId, $start, $end);
            $savings = 0.0;
            foreach ($categoryData as $item) {
                if ($item['category'] === 'Ahorro') {
                    $savings += (float) $item['total'];
                }
            }

            $progress = min(100, round(($savings / $target) * 100, 1));
            $message = "Tu meta de ahorro avanza {$progress}% en el periodo seleccionado.";
            $payload = [
                'target' => $target,
                'saved' => $savings,
                'progress' => $progress,
                'currency' => $profile['currency'] ?? 'MXN',
            ];

            $this->upsert($userId, 'goal', 'info', $message, $payload);
            return;
        }

        if ($goalType === 'debt') {
            $plan = $profile['debt_plan'] ?? null;
            $debtAmount = (float) ($profile['debt_total_amount'] ?? 0);
            if (!$plan || $debtAmount <= 0) {
                $this->clearType($userId, 'goal');
                return;
            }

            $message = "Plan sugerido: pago mensual de {$plan['monthly_payment']} {$profile['currency']} durante {$plan['recommended_months']} meses.";
            $payload = [
                'debt_total' => $debtAmount,
                'plan' => $plan,
                'currency' => $profile['currency'] ?? 'MXN',
            ];

            $this->upsert($userId, 'goal', 'info', $message, $payload);
            return;
        }

        $this->clearType($userId, 'goal');
    }

    public function getActive(int $userId): array
    {
        $statement = $this->db->prepare('
            SELECT id, type, level, message, payload, seen_at, created_at
            FROM alerts
            WHERE user_id = :user_id
            ORDER BY created_at DESC
        ');

        $statement->execute([':user_id' => $userId]);

        $alerts = $statement->fetchAll();

        foreach ($alerts as &$alert) {
            $alert['payload'] = $alert['payload'] ? json_decode($alert['payload'], true) : [];
        }

        return $alerts;
    }

    public function markSeen(int $userId, int $alertId): void
    {
        $statement = $this->db->prepare('
            UPDATE alerts
            SET seen_at = NOW()
            WHERE id = :id AND user_id = :user_id
        ');

        $statement->execute([
            ':id' => $alertId,
            ':user_id' => $userId,
        ]);
    }

    public function clearType(int $userId, string $type): void
    {
        $statement = $this->db->prepare('DELETE FROM alerts WHERE user_id = :user_id AND type = :type');
        $statement->execute([
            ':user_id' => $userId,
            ':type' => $type,
        ]);
    }

    protected function upsert(int $userId, string $type, string $level, string $message, array $payload = []): void
    {
        $existing = $this->findByType($userId, $type);

        if ($existing) {
            $statement = $this->db->prepare('
                UPDATE alerts
                SET level = :level,
                    message = :message,
                    payload = :payload,
                    seen_at = NULL,
                    created_at = NOW()
                WHERE id = :id
            ');

            $statement->execute([
                ':level' => $level,
                ':message' => $message,
                ':payload' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                ':id' => $existing['id'],
            ]);

            return;
        }

        $statement = $this->db->prepare('
            INSERT INTO alerts (user_id, type, level, message, payload)
            VALUES (:user_id, :type, :level, :message, :payload)
        ');

        $statement->execute([
            ':user_id' => $userId,
            ':type' => $type,
            ':level' => $level,
            ':message' => $message,
            ':payload' => json_encode($payload, JSON_UNESCAPED_UNICODE),
        ]);
    }

    protected function findByType(int $userId, string $type): ?array
    {
        $statement = $this->db->prepare('
            SELECT * FROM alerts
            WHERE user_id = :user_id AND type = :type
            ORDER BY created_at DESC
            LIMIT 1
        ');

        $statement->execute([
            ':user_id' => $userId,
            ':type' => $type,
        ]);

        $alert = $statement->fetch();

        return $alert ?: null;
    }
}
