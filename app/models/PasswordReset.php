<?php

namespace App\Models;

use App\Core\Database;
use DateTimeImmutable;
use PDO;

class PasswordReset
{
    protected PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->pdo();
    }

    public function create(int $userId, string $token, DateTimeImmutable $expiresAt): void
    {
        $statement = $this->db->prepare('
            INSERT INTO password_resets (user_id, token, expires_at)
            VALUES (:user_id, :token, :expires_at)
        ');

        $statement->execute([
            ':user_id' => $userId,
            ':token' => $token,
            ':expires_at' => $expiresAt->format('Y-m-d H:i:s'),
        ]);
    }

    public function invalidatePrevious(int $userId): void
    {
        $statement = $this->db->prepare('
            UPDATE password_resets
            SET consumed_at = NOW()
            WHERE user_id = :user_id AND consumed_at IS NULL
        ');

        $statement->execute([':user_id' => $userId]);
    }

    public function findValid(string $token): ?array
    {
        $statement = $this->db->prepare('
            SELECT *
            FROM password_resets
            WHERE token = :token
              AND consumed_at IS NULL
              AND expires_at >= NOW()
            ORDER BY created_at DESC
            LIMIT 1
        ');

        $statement->execute([':token' => $token]);
        $reset = $statement->fetch();

        return $reset ?: null;
    }

    public function consume(int $id): void
    {
        $statement = $this->db->prepare('
            UPDATE password_resets
            SET consumed_at = NOW()
            WHERE id = :id
        ');

        $statement->execute([':id' => $id]);
    }
}
