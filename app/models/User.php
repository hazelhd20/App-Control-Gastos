<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    protected PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->pdo();
    }

    public function create(array $data): int
    {
        $statement = $this->db->prepare('
            INSERT INTO users (name, phone, occupation, email, password_hash)
            VALUES (:name, :phone, :occupation, :email, :password_hash)
        ');

        $statement->execute([
            ':name' => $data['name'],
            ':phone' => $data['phone'] ?? null,
            ':occupation' => $data['occupation'] ?? null,
            ':email' => $data['email'],
            ':password_hash' => $data['password_hash'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute([':email' => $email]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function updateLastLogin(int $id): void
    {
        $statement = $this->db->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id');
        $statement->execute([':id' => $id]);
    }

    public function updateProfile(int $id, array $data): void
    {
        $statement = $this->db->prepare('
            UPDATE users
            SET name = :name,
                phone = :phone,
                occupation = :occupation,
                email = :email,
                updated_at = NOW()
            WHERE id = :id
        ');

        $statement->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':phone' => $data['phone'] ?? null,
            ':occupation' => $data['occupation'] ?? null,
            ':email' => $data['email'],
        ]);
    }

    public function updatePassword(int $id, string $passwordHash): void
    {
        $statement = $this->db->prepare('
            UPDATE users
            SET password_hash = :password,
                updated_at = NOW()
            WHERE id = :id
        ');

        $statement->execute([
            ':id' => $id,
            ':password' => $passwordHash,
        ]);
    }

    public function emailExists(string $email, ?int $ignoreId = null): bool
    {
        $query = 'SELECT COUNT(*) FROM users WHERE email = :email';
        $params = [':email' => $email];

        if ($ignoreId !== null) {
            $query .= ' AND id <> :id';
            $params[':id'] = $ignoreId;
        }

        $statement = $this->db->prepare($query);
        $statement->execute($params);

        return (bool) $statement->fetchColumn();
    }
}
