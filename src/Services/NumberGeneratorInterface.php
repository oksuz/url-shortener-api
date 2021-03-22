<?php

namespace App\Services;

interface NumberGeneratorInterface
{
    function generate(int $min, int $max): int;
}
