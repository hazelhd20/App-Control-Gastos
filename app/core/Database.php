<?php

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    protected PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public static function make(array $config): self
    {
        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $config['driver'] ?? 'mysql',
            $config['host'] ?? '127.0.0.1',
            $config['port'] ?? 3306,
            $config['database'] ?? '',
            $config['charset'] ?? 'utf8mb4'
        );

        try {
            $connection = new PDO(
                $dsn,
                $config['username'] ?? 'root',
                $config['password'] ?? '',
                [
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo establecer la conexion con la base de datos: ' . $exception->getMessage());
        }

        return new self($connection);
    }

    public function pdo(): PDO
    {
        return $this->connection;
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function rollBack(): void
    {
        if ($this->connection->inTransaction()) {
            $this->connection->rollBack();
        }
    }
}
