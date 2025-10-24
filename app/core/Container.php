<?php

namespace App\Core;

use Closure;
use RuntimeException;

class Container
{
    protected array $bindings = [];
    protected array $instances = [];

    public function set(string $abstract, mixed $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function get(string $abstract): mixed
    {
        if (array_key_exists($abstract, $this->instances)) {
            return $this->instances[$abstract];
        }

        if (!array_key_exists($abstract, $this->bindings)) {
            throw new RuntimeException("No binding registered for {$abstract}");
        }

        $concrete = $this->bindings[$abstract];

        if ($concrete instanceof Closure) {
            $object = $concrete($this);
        } elseif (is_callable([$concrete, '__invoke'])) {
            $object = $concrete($this);
        } elseif (is_string($concrete) && class_exists($concrete)) {
            $object = new $concrete();
        } else {
            $object = $concrete;
        }

        return $this->instances[$abstract] = $object;
    }
}
