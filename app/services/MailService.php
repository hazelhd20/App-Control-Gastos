<?php

namespace App\Services;

use App\Core\Config;
use DateTimeImmutable;

class MailService
{
    protected Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function send(string $to, string $subject, string $body): void
    {
        $driver = $this->config->get('mail.driver', 'log');

        if ($driver === 'log') {
            $this->logEmail($to, $subject, $body);
            return;
        }

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . $this->formattedFrom(),
        ];

        @mail($to, $subject, $body, implode("\r\n", $headers));
    }

    protected function logEmail(string $to, string $subject, string $body): void
    {
        $path = rtrim($this->config->get('mail.log_path', __DIR__ . '/../../storage/emails'), '/');
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filename = sprintf(
            '%s_%s.log',
            (new DateTimeImmutable())->format('Ymd_His'),
            preg_replace('/[^a-z0-9]+/i', '-', $to)
        );

        $content = "Para: {$to}\nAsunto: {$subject}\n\n{$body}\n";
        file_put_contents($path . '/' . $filename, $content);
    }

    protected function formattedFrom(): string
    {
        $fromAddress = $this->config->get('mail.from.address', 'no-reply@example.com');
        $fromName = $this->config->get('mail.from.name', 'Aplicacion');

        return "{$fromName} <{$fromAddress}>";
    }
}
