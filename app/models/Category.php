<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Category
{
    protected PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->pdo();
    }

    public function forUser(int $userId, string $type = 'expense'): array
    {
        $statement = $this->db->prepare('
            SELECT id, name, type
            FROM categories
            WHERE (user_id = :user_id OR user_id IS NULL)
              AND type = :type
            ORDER BY name ASC
        ');

        $statement->execute([
            ':user_id' => $userId,
            ':type' => $type,
        ]);

        return $statement->fetchAll();
    }

    public function findByName(int $userId, string $name, string $type): ?array
    {
        $statement = $this->db->prepare('
            SELECT id, name, type
            FROM categories
            WHERE LOWER(name) = :name
              AND type = :type
              AND (user_id = :user_id OR user_id IS NULL)
            ORDER BY user_id DESC
            LIMIT 1
        ');

        $statement->execute([
            ':name' => mb_strtolower($name, 'UTF-8'),
            ':type' => $type,
            ':user_id' => $userId,
        ]);

        $category = $statement->fetch();

        return $category ?: null;
    }

    public function create(int $userId, string $name, string $type): int
    {
        $statement = $this->db->prepare('
            INSERT INTO categories (user_id, name, type)
            VALUES (:user_id, :name, :type)
        ');

        $statement->execute([
            ':user_id' => $userId,
            ':name' => ucfirst(trim($name)),
            ':type' => $type,
        ]);

        return (int) $this->db->lastInsertId();
    }
}
