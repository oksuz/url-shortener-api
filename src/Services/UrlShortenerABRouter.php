<?php


namespace App\Services;

use App\Exception\ABTestException;
use App\Model\RatioHolder;

class UrlShortenerABRouter
{

    /**
     * @var array|RatioHolder[]
     */
    protected array $ratioHolder;

    /**
     * @var NumberGeneratorInterface
     */
    protected NumberGeneratorInterface $numberGenerator;

    /**
     * UrlShortenerABRouter constructor.
     * @param array $config
     * @param NumberGeneratorInterface $numberGenerator
     */
    public function __construct(array $config, NumberGeneratorInterface $numberGenerator)
    {
        $this->numberGenerator = $numberGenerator;
        $this->ratioHolder = [
            new RatioHolder($config['a']['ratio'], $config['a']['provider']),
            new RatioHolder($config['b']['ratio'], $config['b']['provider'])
        ];

        \usort($this->ratioHolder, function (RatioHolder $a, RatioHolder $b) {
            return $a->getRatio() < $b->getRatio() ? -1 : 1;
        });
    }


    /**
     * @param string $decoded
     * @return string
     * @throws ABTestException
     */
    public function shortUrl(string $decoded): string
    {
        $provider = $this->getProvider();
        if ($provider === null) {
            throw new ABTestException('There is no eligible provider found by given ratios');
        }

        return $provider->encode($decoded);
    }

    protected function getProvider(): ?ShortenerInterface
    {
        $rand = $this->numberGenerator->generate(0, 100);
        $rangeStart = 0;
        foreach ($this->ratioHolder as $ratioHolder) {
            if ($rand >= $rangeStart && $rand <= ($ratioHolder->getRatio() + $rangeStart)) {
                return $ratioHolder->getShortener();
            } else {
                $rangeStart += $ratioHolder->getRatio();
            }
        }

        return null;
    }
}
