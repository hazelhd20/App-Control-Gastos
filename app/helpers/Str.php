<?php

namespace App\Helpers;

class Str
{
    public static function random(int $length = 40): string
    {
        $bytes = (int) ceil($length / 2);
        return substr(bin2hex(random_bytes($bytes)), 0, $length);
    }
}
