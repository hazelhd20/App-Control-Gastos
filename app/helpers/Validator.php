<?php

namespace App\Helpers;

class Validator
{
    protected array $data;
    protected array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function make(array $data): self
    {
        return new self($data);
    }

    public function required(string $field, ?string $message = null): self
    {
        $value = $this->data[$field] ?? null;
        if ($value === null || $value === '') {
            $this->errors[$field][] = $message ?? 'Este campo es obligatorio';
        }

        return $this;
    }

    public function email(string $field, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';
        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = $message ?? 'El correo electronico no es valido';
        }

        return $this;
    }

    public function min(string $field, int $min, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';
        if (is_string($value) && strlen($value) < $min) {
            $this->errors[$field][] = $message ?? "Debe tener al menos {$min} caracteres";
        }

        return $this;
    }

    public function matches(string $field, string $other, ?string $message = null): self
    {
        if (($this->data[$field] ?? null) !== ($this->data[$other] ?? null)) {
            $this->errors[$field][] = $message ?? 'Los valores no coinciden';
        }

        return $this;
    }

    public function regex(string $field, string $pattern, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';
        if ($value && !preg_match($pattern, $value)) {
            $this->errors[$field][] = $message ?? 'Formato invalido';
        }
        return $this;
    }

    public function maxLength(string $field, int $max, ?string $message = null): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && $value !== '' && mb_strlen((string) $value, 'UTF-8') > $max) {
            $this->errors[$field][] = $message ?? "Debe tener maximo {$max} caracteres";
        }

        return $this;
    }

    public function numeric(string $field, ?string $message = null): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && !is_numeric($value)) {
            $this->errors[$field][] = $message ?? 'Debe ser un numero';
        }

        return $this;
    }

    public function minValue(string $field, float|int $min, ?string $message = null): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && $value !== '' && (float) $value < $min) {
            $this->errors[$field][] = $message ?? "Debe ser mayor o igual a {$min}";
        }

        return $this;
    }

    public function integer(string $field, ?string $message = null): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && $value !== '' && filter_var($value, FILTER_VALIDATE_INT) === false) {
            $this->errors[$field][] = $message ?? 'Debe ser un numero entero';
        }

        return $this;
    }

    public function in(string $field, array $options, ?string $message = null): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && !in_array($value, $options, true)) {
            $this->errors[$field][] = $message ?? 'Valor invalido';
        }

        return $this;
    }

    public function arrayNotEmpty(string $field, ?string $message = null): self
    {
        $value = $this->data[$field] ?? [];
        if (!is_array($value) || count(array_filter($value)) === 0) {
            $this->errors[$field][] = $message ?? 'Selecciona al menos una opcion';
        }

        return $this;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }
}
