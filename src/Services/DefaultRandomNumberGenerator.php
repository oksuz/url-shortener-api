<?php


namespace App\Services;

class DefaultRandomNumberGenerator implements NumberGeneratorInterface
{

    public function generate(int $min, int $max): int
    {
        return mt_rand($min, $max);
    }
}
