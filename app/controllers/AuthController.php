<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Helpers\Str;
use App\Helpers\Validator;
use App\Models\PasswordReset;
use App\Models\User;
use App\Services\MailService;
use DateInterval;
use DateTimeImmutable;

class AuthController extends Controller
{
    public function showLogin(Request $request): void
    {
        if ($this->auth->check()) {
            $this->response->redirect('/App-Control-Gastos/public');
            return;
        }

        $this->render('auth/login', [
            'title' => 'Iniciar sesion',
        ], 'layouts/guest');
    }

    public function login(Request $request): void
    {
        if (!$this->validateToken($request)) {
            $this->session->flash('error', 'Token invalido, intenta nuevamente.');
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        $validator = Validator::make($request->only(['email', 'password']))
            ->required('email', 'Ingresa tu correo electronico.')
            ->email('email')
            ->required('password', 'La contrasena es obligatoria.');

        if ($validator->fails()) {
            $this->session->flash('error', 'Revisa tus credenciales e intenta de nuevo.');
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        $email = strtolower(trim($request->input('email')));
        $password = $request->input('password');

        if (!$this->auth->attempt($email, $password)) {
            $this->session->flash('error', 'Credenciales invalidas.');
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        $this->session->flash('success', 'Inicio de sesion exitoso.');
        $this->response->redirect('/App-Control-Gastos/public');
    }

    public function showRegister(Request $request): void
    {
        if ($this->auth->check()) {
            $this->response->redirect('/App-Control-Gastos/public');
            return;
        }

        $this->render('auth/register', [
            'title' => 'Crear cuenta',
        ], 'layouts/guest');
    }

    public function register(Request $request): void
    {
        if (!$this->validateToken($request)) {
            $this->session->flash('error', 'Token invalido, intenta nuevamente.');
            $this->response->redirect('/App-Control-Gastos/public/registro');
            return;
        }

        $data = $request->only(['name', 'phone', 'occupation', 'email', 'password', 'password_confirmation']);
        $validator = Validator::make($data)
            ->required('name', 'Tu nombre completo es obligatorio.')
            ->required('phone', 'Ingresa tu numero telefonico.')
            ->required('occupation', 'Indica tu ocupacion.')
            ->required('email', 'El correo es obligatorio.')
            ->email('email')
            ->required('password', 'La contrasena es obligatoria.')
            ->min('password', 8, 'La contrasena debe tener al menos 8 caracteres.')
            ->regex('password', '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', 'La contrasena debe incluir mayusculas, numeros y un caracter especial.')
            ->required('password_confirmation', 'Confirma tu contrasena.')
            ->matches('password_confirmation', 'password', 'Las contrasenas deben coincidir.');

        if ($validator->fails()) {
            $this->session->flash('error', 'Revisa los campos marcados e intenta nuevamente.');
            $this->response->redirect('/App-Control-Gastos/public/registro');
            return;
        }

        $userModel = new User($this->db());

        if ($userModel->findByEmail($data['email'])) {
            $this->session->flash('error', 'Este correo ya esta registrado.');
            $this->response->redirect('/App-Control-Gastos/public/registro');
            return;
        }

        $hash = password_hash($data['password'], PASSWORD_BCRYPT, [
            'cost' => $this->config()->get('security.password_cost', 12),
        ]);

        $userId = $userModel->create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'occupation' => $data['occupation'],
            'email' => strtolower(trim($data['email'])),
            'password_hash' => $hash,
        ]);

        $this->session->flash('success', 'Registro exitoso, ahora puedes configurar tu perfil financiero.');
        $this->auth->attempt($data['email'], $data['password']);
        $this->response->redirect('/App-Control-Gastos/public/perfil/configuracion-inicial');
    }

    public function logout(Request $request): void
    {
        if (!$this->validateToken($request)) {
            $this->session->flash('error', 'Accion no permitida.');
            $this->response->redirect('/App-Control-Gastos/public');
            return;
        }

        $this->auth->logout();
        $this->session->flash('info', 'Sesion cerrada con exito.');
        $this->response->redirect('/App-Control-Gastos/public/login');
    }

    public function showForgot(Request $request): void
    {
        $this->render('auth/forgot', [
            'title' => 'Recuperar contrasena',
        ], 'layouts/guest');
    }

    public function sendResetLink(Request $request): void
    {
        if (!$this->validateToken($request)) {
            $this->session->flash('error', 'Token invalido, intenta nuevamente.');
            $this->response->redirect('/App-Control-Gastos/public/recuperar');
            return;
        }

        $email = trim($request->input('email'));
        $validator = Validator::make(['email' => $email])
            ->required('email', 'Ingresa tu correo.')
            ->email('email');

        if ($validator->fails()) {
            $this->session->flash('error', 'Ingresa un correo valido.');
            $this->response->redirect('/App-Control-Gastos/public/recuperar');
            return;
        }

        $userModel = new User($this->db());
        $user = $userModel->findByEmail($email);

        if ($user) {
            $this->sendPasswordResetEmail((int) $user['id'], $user['email'], $user['name']);
        }

        $this->session->flash('info', 'Si el correo existe recibiras instrucciones para restablecer tu contrasena.');
        $this->response->redirect('/App-Control-Gastos/public/login');
    }

    public function showResetForm(Request $request): void
    {
        $token = $request->input('token');
        if (!$token) {
            $this->session->flash('error', 'Enlace de restablecimiento invalido.');
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        $resetModel = new PasswordReset($this->db());
        $record = $resetModel->findValid($token);

        if (!$record) {
            $this->session->flash('error', 'El enlace expiro o ya fue utilizado.');
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        $this->render('auth/reset', [
            'title' => 'Restablecer contrasena',
            'token' => $token,
        ], 'layouts/guest');
    }

    public function resetPassword(Request $request): void
    {
        if (!$this->validateToken($request)) {
            $this->session->flash('error', 'Token invalido, intenta nuevamente.');
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        $data = $request->only(['token', 'password', 'password_confirmation']);

        $validator = Validator::make($data)
            ->required('token', 'El token es obligatorio.')
            ->required('password', 'Ingresa una contrasena.')
            ->min('password', 8)
            ->regex('password', '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/')
            ->required('password_confirmation', 'Confirma tu contrasena.')
            ->matches('password_confirmation', 'password');

        if ($validator->fails()) {
            $this->session->flash('error', 'Verifica la nueva contrasena e intenta nuevamente.');
            $this->response->redirect('/App-Control-Gastos/public/restablecer?token=' . urlencode($data['token'] ?? ''));
            return;
        }

        $resetModel = new PasswordReset($this->db());
        $record = $resetModel->findValid($data['token']);

        if (!$record) {
            $this->session->flash('error', 'El enlace expiro o ya fue utilizado.');
            $this->response->redirect('/App-Control-Gastos/public/login');
            return;
        }

        $userModel = new User($this->db());
        $hash = password_hash($data['password'], PASSWORD_BCRYPT, [
            'cost' => $this->config()->get('security.password_cost', 12),
        ]);

        $userModel->updatePassword((int) $record['user_id'], $hash);
        $resetModel->consume((int) $record['id']);

        $this->session->flash('success', 'Contrasena actualizada correctamente. Ahora puedes iniciar sesion.');
        $this->response->redirect('/App-Control-Gastos/public/login');
    }

    protected function sendPasswordResetEmail(int $userId, string $email, string $name): void
    {
        $token = Str::random(64);
        $expiresIn = $this->config()->get('security.reset_token_lifetime', 300);

        $resetModel = new PasswordReset($this->db());
        $resetModel->invalidatePrevious($userId);
        $resetModel->create(
            $userId,
            $token,
            (new DateTimeImmutable())->add(new DateInterval('PT' . $expiresIn . 'S'))
        );

        $resetUrl = $this->config()->get('app.url') . '/restablecer?token=' . urlencode($token);
        $subject = 'Restablece tu contrasena';
        $body = "<p>Hola {$name},</p>";
        $body .= '<p>Recibimos una solicitud para restablecer tu contrasena. Puedes hacerlo usando el siguiente boton:</p>';
        $body .= "<p><a href=\"{$resetUrl}\">Restablecer contrasena</a></p>";
        $body .= '<p>El enlace expirara en 5 minutos. Si no solicitaste este acceso puedes ignorar este mensaje.</p>';

        $mail = $this->container->get(MailService::class);
        $mail->send($email, $subject, $body);
    }

    protected function validateToken(Request $request): bool
    {
        $token = $request->input('_token');
        return hash_equals($this->session->token(), (string) $token);
    }
}
