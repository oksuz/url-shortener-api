<?php

namespace App\Tests\Services;

use App\Exception\ABTestException;
use App\Services\NumberGeneratorInterface;
use App\Services\ShortenerInterface;
use App\Services\UrlShortenerABRouter;
use PHPUnit\Framework\TestCase;

class UrlShortenerABRouterTest extends TestCase
{
    private static string $URL_FOR_ENCODE = 'https://www.google.com/';

    private ShortenerInterface $shortener1;
    private ShortenerInterface $shortener2;
    private NumberGeneratorInterface $numberGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->shortener1 = $this->createMock(ShortenerInterface::class);
        $this->shortener2 = $this->createMock(ShortenerInterface::class);
        $this->numberGenerator = $this->createMock(NumberGeneratorInterface::class);
    }

    public function testShortUrl(): void
    {
        $ABrouter = new UrlShortenerABRouter([
            'a' => [
                'ratio' => 30,
                'provider' => $this->shortener1
            ],
            'b' => [
                'ratio' => 70,
                'provider' => $this->shortener2
            ]
        ], $this->numberGenerator);


        $this->numberGenerator->expects($this->at(0))->method('generate')->willReturn(2);
        $this->numberGenerator->expects($this->at(1))->method('generate')->willReturn(30);
        $this->numberGenerator->expects($this->at(2))->method('generate')->willReturn(70);
        $this->numberGenerator->expects($this->at(3))->method('generate')->willReturn(100);

        $this->shortener1->expects($this->exactly(2))->method('encode')->withAnyParameters()->willReturn('test1');
        $ABrouter->shortUrl(self::$URL_FOR_ENCODE);
        $ABrouter->shortUrl(self::$URL_FOR_ENCODE);

        $this->shortener2->expects($this->exactly(2))->method('encode')->withAnyParameters()->willReturn('test2');
        $ABrouter->shortUrl(self::$URL_FOR_ENCODE);
        $ABrouter->shortUrl(self::$URL_FOR_ENCODE);
    }

    public function testExceptin()
    {
        $ABrouter = new UrlShortenerABRouter([
            'a' => [
                'ratio' => 30,
                'provider' => $this->shortener1
            ],
            'b' => [
                'ratio' => 70,
                'provider' => $this->shortener2
            ]
        ], $this->numberGenerator);

        $this->numberGenerator->expects($this->exactly(1))->method('generate')->willReturn(101);
        $this->expectException(ABTestException::class);

        $ABrouter->shortUrl(self::$URL_FOR_ENCODE);
    }

}
