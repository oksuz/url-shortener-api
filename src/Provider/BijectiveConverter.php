<?php


namespace App\Provider;

class BijectiveConverter
{
    private static string $alphabet = "_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    private array $words;

    /**
     * BijectiveUrlShortener constructor.
     */
    public function __construct()
    {
        $this->words = \str_split(self::$alphabet);
    }

    public function encode(string $decoded): string
    {
        $intValue = intval($decoded);
        if ($intValue === 0) {
            throw new \InvalidArgumentException(sprintf('%s should be higher than 0', $decoded));
        }

        $result = [];
        $base = \count($this->words);

        while ($intValue > 0) {
            $result[] = $this->words[($intValue % $base)];
            $intValue = floor($intValue / $base);
        }

        return \join('', \array_reverse($result));
    }

    public function decode(string $encoded): string
    {
        $input = str_split($encoded);
        $base = \count($this->words);
        $decoded = 0;
        foreach ($input as $char) {
            $pos = array_search($char, $this->words);
            $decoded = $decoded * $base + $pos;
        }

        return $decoded;
    }
}
