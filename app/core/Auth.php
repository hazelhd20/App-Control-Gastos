<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    protected Session $session;
    protected Database $database;

    public function __construct(Session $session, Database $database)
    {
        $this->session = $session;
        $this->database = $database;
    }

    public function attempt(string $email, string $password): bool
    {
        $user = (new User($this->database))->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        $this->session->regenerate();
        $this->session->set('user_id', $user['id']);

        $userModel = new User($this->database);
        $userModel->updateLastLogin($user['id']);

        return true;
    }

    public function check(): bool
    {
        return (bool) $this->session->get('user_id');
    }

    public function user(): ?array
    {
        $id = $this->session->get('user_id');
        if (!$id) {
            return null;
        }

        return (new User($this->database))->find($id);
    }

    public function id(): ?int
    {
        return $this->session->get('user_id');
    }

    public function logout(): void
    {
        $this->session->forget('user_id');
        $this->session->regenerate();
    }
}
