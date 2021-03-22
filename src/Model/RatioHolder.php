<?php


namespace App\Model;

use App\Services\ShortenerInterface;

class RatioHolder
{

    private int $ratio;
    private ShortenerInterface $shortener;

    /**
     * RatioHolder constructor.
     * @param int $ratio
     * @param ShortenerInterface $shortener
     */
    public function __construct(int $ratio, ShortenerInterface $shortener)
    {
        $this->ratio = $ratio;
        $this->shortener = $shortener;
    }

    /**
     * @return int
     */
    public function getRatio(): int
    {
        return $this->ratio;
    }

    /**
     * @return ShortenerInterface
     */
    public function getShortener(): ShortenerInterface
    {
        return $this->shortener;
    }
}
