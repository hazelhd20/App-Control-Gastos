<?php

namespace App\Core;

use App\Core\Auth;
use App\Core\Config;
use App\Core\Container;
use App\Core\Database;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;

class Controller
{
    protected Container $container;
    protected View $view;
    protected Session $session;
    protected Response $response;
    protected Auth $auth;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->view = new View();
        $this->session = $container->get(Session::class);
        $this->response = new Response();
        $this->auth = $container->get(Auth::class);
    }

    protected function config(): Config
    {
        return $this->container->get(Config::class);
    }

    protected function db(): Database
    {
        return $this->container->get(Database::class);
    }

    protected function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void
    {
        $this->view->render($view, array_merge([
            'auth' => $this->auth,
            'session' => $this->session,
            'csrfToken' => $this->session->token(),
        ], $data), $layout);
    }
}
