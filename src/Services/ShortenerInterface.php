<?php


namespace App\Services;

interface ShortenerInterface
{
    public function encode(string $decoded): ?string;

    public function decode(string $encoded): ?string;
}
