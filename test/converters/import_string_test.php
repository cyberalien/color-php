<?php

use \CyberAlien\Color\Color;

class ColorConvertersImportStringTest extends \PHPUnit\Framework\TestCase
{
    public function testRGB()
    {
        // Test spacing and case
        $test = Color::fromString('rgb(10, 20, 30)');
        $this->assertNotNull($test);
        $this->assertEquals([10, 20, 30, 1], $test->getRGBA());

        $test = Color::fromString('RGB(10,20,30)');
        $this->assertNotNull($test);
        $this->assertEquals([10, 20, 30, 1], $test->getRGBA());

        $test = Color::fromString("rgb  (\t10  ,   20  ,   30  )");
        $this->assertNotNull($test);
        $this->assertEquals([10, 20, 30, 1], $test->getRGBA());

        // Test percentages
        $test = Color::fromString('rgb(10%, 20%, 30%)');
        $this->assertNotNull($test);
        $this->assertEquals([25.5, 51, 76.5, 1], $test->getRGBA());

        // Test RGBA
        $test = Color::fromString('rgba(10, 20, 30, .5)');
        $this->assertNotNull($test);
        $this->assertEquals([10, 20, 30, .5], $test->getRGBA());
    }

    public function testHSL()
    {
        $test = Color::fromString('hsl(10, 20%, 30%)');
        $this->assertNotNull($test);
        $this->assertEquals([10, 20, 30, 1], $test->getHSLA());

        $test = Color::fromString('HSLA ( 10 , 20% , 30% , .2 )');
        $this->assertNotNull($test);
        $this->assertEquals([10, 20, 30, .2], $test->getHSLA());

        $test = Color::fromString('hsl(10.4, 20.7%, 30.1%)');
        $this->assertNotNull($test);
        $this->assertEquals([10, 21, 30], $test->getHSL(true));
    }

    public function testHex()
    {
        // 4 characters
        $test = Color::fromString('#1a3');
        $this->assertNotNull($test);
        $this->assertEquals([17, 170, 51, 1], $test->getRGBA());

        // 8 characters
        $test = Color::fromString('a51234f6');
        $this->assertNotNull($test);
        $this->assertEquals([18, 52, 246, 165 / 255], $test->getRGBA());
    }

    public function testKeyword()
    {
        $test = Color::fromString('skyblue');
        $this->assertNotNull($test);
        $this->assertEquals([135, 206, 235, 1], $test->getRGBA());
    }

    public function testInvalidString()
    {
        $this->assertNull(Color::fromString('')); // Empty string
        $this->assertNull(Color::fromString('1')); // Invalid string
        $this->assertNull(Color::fromString('rgb(10%, 20%, 30)')); // Can't mix percentages and raw values
        $this->assertNull(Color::fromString('rgb(10, 20, 30%)')); // Can't mix percentages and raw values
        $this->assertNull(Color::fromString('hsl(10%, 20%, 30%)')); // Hue can't be percentage
        $this->assertNull(Color::fromString('hsl(10, 20, 30%)')); // Saturation must be percentage
        $this->assertNull(Color::fromString('hsl(10, 20%, 30)')); // Lightness must be percentage
        $this->assertNull(Color::fromString('hsl(10, 20%, 0F%)')); // Invalid character
        $this->assertNull(Color::fromString('rgb(10%, 20%, 30%, 40%)')); // Too many components
        $this->assertNull(Color::fromString('rgba(10%, 20%, 30%, 40%, 50%)')); // Too many components
        $this->assertNull(Color::fromString('hsl(10%, 20%, 30%, 40%)')); // Too many components
        $this->assertNull(Color::fromString('hsla(10%, 20%, 30%, 40%, 50%)')); // Too many components
        $this->assertNull(Color::fromString('rgb(10%, 20%)')); // Too few components
        $this->assertNull(Color::fromString('rgba(10%, 20%, 30%)')); // Too few components
        $this->assertNull(Color::fromString('hsl(10, 20%)')); // Too few components
        $this->assertNull(Color::fromString('hsla(10, 20%, 30%)')); // Too few components

        // Color adjustment strings
        $this->assertNull(Color::fromString('rgba(#123, 30%)'));
        $this->assertNull(Color::fromString('rgba(rgb(10, 20, 30), 30%)'));
        $this->assertNull(Color::fromString('rgb(@primaryMedium, 20, 30)'));
        $this->assertNull(Color::fromString('hsl(hue(#123), 20, 30)'));
    }
}
