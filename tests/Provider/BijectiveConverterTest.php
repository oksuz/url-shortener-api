<?php


namespace App\Tests\Provider;


use App\Provider\BijectiveConverter;
use PHPUnit\Framework\TestCase;

class BijectiveConverterTest extends TestCase
{

    public function testEncodeWhenProvidedValueZero()
    {
        $instance = new BijectiveConverter();
        $this->expectException(\InvalidArgumentException::class);
        $instance->encode(0);
    }

    public function testEncodeWithDifferentValues()
    {
        $instance = new BijectiveConverter();
        $result1 = $instance->encode(1);
        $result2 = $instance->encode(2);
        $result3 = $instance->encode(123456);
        $result4 = $instance->encode(2147483647);
        $result5 = $instance->encode(9999999999);


        $this->assertEquals('a', $result1);
        $this->assertEquals('b', $result2);
        $this->assertEquals('EfM', $result3);
        $this->assertEquals('bjttja', $result4);
        $this->assertEquals('jdXDtS', $result5);
    }

    public function testDecodeWithDifferentValues()
    {
        $instance = new BijectiveConverter();
        $result1 = $instance->decode('a');
        $result2 = $instance->decode('b');
        $result3 = $instance->decode('EfM');
        $result4 = $instance->decode('bjttja');
        $result5 = $instance->decode('jdXDtS');

        $this->assertEquals(1, $result1);
        $this->assertEquals(2, $result2);
        $this->assertEquals(123456, $result3);
        $this->assertEquals(2147483647, $result4);
        $this->assertEquals(9999999999, $result5);
    }
}