<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class FinancialProfile
{
    protected PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->pdo();
    }

    public function findByUserId(int $userId): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM financial_profiles WHERE user_id = :user_id LIMIT 1');
        $statement->execute([':user_id' => $userId]);
        $profile = $statement->fetch();

        if ($profile) {
            $profile['spending_media'] = $profile['spending_media'] ? json_decode($profile['spending_media'], true) : [];
            $profile['debt_plan'] = $profile['debt_plan'] ? json_decode($profile['debt_plan'], true) : null;
        }

        return $profile ?: null;
    }

    public function create(int $userId, array $data): void
    {
        $statement = $this->db->prepare('
            INSERT INTO financial_profiles (
                user_id, monthly_income, extra_income, start_date, currency, spending_media,
                goal_type, goal_description, goal_meta_amount, goal_meta_months,
                debt_total_amount, debt_plan, spending_limit_mode, spending_limit_amount, auto_limit_ratio
            )
            VALUES (
                :user_id, :monthly_income, :extra_income, :start_date, :currency, :spending_media,
                :goal_type, :goal_description, :goal_meta_amount, :goal_meta_months,
                :debt_total_amount, :debt_plan, :spending_limit_mode, :spending_limit_amount, :auto_limit_ratio
            )
        ');

        $statement->execute([
            ':user_id' => $userId,
            ':monthly_income' => $data['monthly_income'],
            ':extra_income' => $data['extra_income'] ?? 0,
            ':start_date' => $data['start_date'],
            ':currency' => $data['currency'],
            ':spending_media' => json_encode($data['spending_media'] ?? [], JSON_UNESCAPED_UNICODE),
            ':goal_type' => $data['goal_type'],
            ':goal_description' => $data['goal_description'] ?? null,
            ':goal_meta_amount' => $data['goal_meta_amount'] ?? null,
            ':goal_meta_months' => $data['goal_meta_months'] ?? null,
            ':debt_total_amount' => $data['debt_total_amount'] ?? null,
            ':debt_plan' => isset($data['debt_plan']) ? json_encode($data['debt_plan'], JSON_UNESCAPED_UNICODE) : null,
            ':spending_limit_mode' => $data['spending_limit_mode'],
            ':spending_limit_amount' => $data['spending_limit_amount'],
            ':auto_limit_ratio' => $data['auto_limit_ratio'] ?? null,
        ]);
    }

    public function update(int $userId, array $data): void
    {
        $statement = $this->db->prepare('
            UPDATE financial_profiles
            SET monthly_income = :monthly_income,
                extra_income = :extra_income,
                start_date = :start_date,
                currency = :currency,
                spending_media = :spending_media,
                goal_type = :goal_type,
                goal_description = :goal_description,
                goal_meta_amount = :goal_meta_amount,
                goal_meta_months = :goal_meta_months,
                debt_total_amount = :debt_total_amount,
                debt_plan = :debt_plan,
                spending_limit_mode = :spending_limit_mode,
                spending_limit_amount = :spending_limit_amount,
                auto_limit_ratio = :auto_limit_ratio,
                updated_at = NOW()
            WHERE user_id = :user_id
        ');

        $statement->execute([
            ':user_id' => $userId,
            ':monthly_income' => $data['monthly_income'],
            ':extra_income' => $data['extra_income'] ?? 0,
            ':start_date' => $data['start_date'],
            ':currency' => $data['currency'],
            ':spending_media' => json_encode($data['spending_media'] ?? [], JSON_UNESCAPED_UNICODE),
            ':goal_type' => $data['goal_type'],
            ':goal_description' => $data['goal_description'] ?? null,
            ':goal_meta_amount' => $data['goal_meta_amount'] ?? null,
            ':goal_meta_months' => $data['goal_meta_months'] ?? null,
            ':debt_total_amount' => $data['debt_total_amount'] ?? null,
            ':debt_plan' => isset($data['debt_plan']) ? json_encode($data['debt_plan'], JSON_UNESCAPED_UNICODE) : null,
            ':spending_limit_mode' => $data['spending_limit_mode'],
            ':spending_limit_amount' => $data['spending_limit_amount'],
            ':auto_limit_ratio' => $data['auto_limit_ratio'] ?? null,
        ]);
    }

    public function existsForUser(int $userId): bool
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM financial_profiles WHERE user_id = :user_id');
        $statement->execute([':user_id' => $userId]);

        return (bool) $statement->fetchColumn();
    }
}
