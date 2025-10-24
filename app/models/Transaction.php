<?php

namespace App\Models;

use App\Core\Database;
use DateTimeImmutable;
use PDO;

class Transaction
{
    protected PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->pdo();
    }

    public function create(int $userId, array $data): int
    {
        $statement = $this->db->prepare('
            INSERT INTO transactions (
                user_id, type, category, amount, payment_method, happened_on, description
            )
            VALUES (
                :user_id, :type, :category, :amount, :payment_method, :happened_on, :description
            )
        ');

        $statement->execute([
            ':user_id' => $userId,
            ':type' => $data['type'],
            ':category' => $data['category'],
            ':amount' => $data['amount'],
            ':payment_method' => $data['payment_method'],
            ':happened_on' => $data['happened_on'],
            ':description' => $data['description'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function delete(int $userId, int $transactionId): void
    {
        $statement = $this->db->prepare('
            DELETE FROM transactions
            WHERE id = :id AND user_id = :user_id
        ');

        $statement->execute([
            ':id' => $transactionId,
            ':user_id' => $userId,
        ]);
    }

    public function all(int $userId, array $filters = []): array
    {
        $query = '
            SELECT id, type, category, amount, payment_method, happened_on, description, created_at
            FROM transactions
            WHERE user_id = :user_id
        ';

        $params = [
            ':user_id' => $userId,
        ];

        if (!empty($filters['type']) && in_array($filters['type'], ['income', 'expense'], true)) {
            $query .= ' AND type = :type';
            $params[':type'] = $filters['type'];
        }

        if (!empty($filters['category'])) {
            $query .= ' AND category = :category';
            $params[':category'] = $filters['category'];
        }

        if (!empty($filters['payment_method'])) {
            $query .= ' AND payment_method = :payment_method';
            $params[':payment_method'] = $filters['payment_method'];
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $startDate = DateTimeImmutable::createFromFormat('Y-m-d', $filters['start_date']);
            $endDate = DateTimeImmutable::createFromFormat('Y-m-d', $filters['end_date']);

            if ($startDate && $endDate) {
                if ($endDate < $startDate) {
                    [$startDate, $endDate] = [$endDate, $startDate];
                }

                $query .= ' AND happened_on BETWEEN :start_date AND :end_date';
                $params[':start_date'] = $startDate->format('Y-m-d');
                $params[':end_date'] = $endDate->format('Y-m-d');
            }
        } elseif (!empty($filters['month']) && preg_match('/^\d{4}-\d{2}$/', $filters['month'])) {
            $start = DateTimeImmutable::createFromFormat('Y-m-d', $filters['month'] . '-01');
            if ($start) {
                $end = $start->modify('last day of this month');
                $query .= ' AND happened_on BETWEEN :start_date AND :end_date';
                $params[':start_date'] = $start->format('Y-m-d');
                $params[':end_date'] = $end->format('Y-m-d');
            }
        }

        $query .= ' ORDER BY happened_on DESC, created_at DESC';

        $statement = $this->db->prepare($query);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function recent(int $userId, int $limit = 5): array
    {
        $statement = $this->db->prepare('
            SELECT id, type, category, amount, payment_method, happened_on, description
            FROM transactions
            WHERE user_id = :user_id
            ORDER BY happened_on DESC, created_at DESC
            LIMIT :limit
        ');

        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function totalsByType(int $userId, string $type, DateTimeImmutable $start, DateTimeImmutable $end): float
    {
        $statement = $this->db->prepare('
            SELECT COALESCE(SUM(amount), 0)
            FROM transactions
            WHERE user_id = :user_id
              AND type = :type
              AND happened_on BETWEEN :start AND :end
        ');

        $statement->execute([
            ':user_id' => $userId,
            ':type' => $type,
            ':start' => $start->format('Y-m-d'),
            ':end' => $end->format('Y-m-d'),
        ]);

        return (float) $statement->fetchColumn();
    }

    public function totalsByCategory(int $userId, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $statement = $this->db->prepare('
            SELECT category, type, SUM(amount) as total
            FROM transactions
            WHERE user_id = :user_id
              AND happened_on BETWEEN :start AND :end
            GROUP BY category, type
            ORDER BY total DESC
        ');

        $statement->execute([
            ':user_id' => $userId,
            ':start' => $start->format('Y-m-d'),
            ':end' => $end->format('Y-m-d'),
        ]);

        return $statement->fetchAll();
    }

    public function totalsByPaymentMethod(int $userId, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $statement = $this->db->prepare('
            SELECT payment_method, SUM(amount) as total
            FROM transactions
            WHERE user_id = :user_id
              AND type = "expense"
              AND happened_on BETWEEN :start AND :end
            GROUP BY payment_method
            ORDER BY total DESC
        ');

        $statement->execute([
            ':user_id' => $userId,
            ':start' => $start->format('Y-m-d'),
            ':end' => $end->format('Y-m-d'),
        ]);

        return $statement->fetchAll();
    }

    public function monthlyEvolution(int $userId, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $statement = $this->db->prepare('
            SELECT DATE_FORMAT(happened_on, "%Y-%m") as month,
                   SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income,
                   SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense
            FROM transactions
            WHERE user_id = :user_id
              AND happened_on BETWEEN :start AND :end
            GROUP BY DATE_FORMAT(happened_on, "%Y-%m")
            ORDER BY month ASC
        ');

        $statement->execute([
            ':user_id' => $userId,
            ':start' => $start->format('Y-m-d'),
            ':end' => $end->format('Y-m-d'),
        ]);

        return $statement->fetchAll();
    }
}
