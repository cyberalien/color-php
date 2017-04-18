<?php

use \CyberAlien\Color\Color;

class ColorConvertTest extends \PHPUnit\Framework\TestCase
{
    public function testRGBtoHSL()
    {
        // Test RGB to HSL conversion
        $test = new Color();
        $test->setRGBA(255, 128, 0, .2);
        $this->assertEquals([255, 128, 0, .2], $test->getRGBA());
        $this->assertEquals(30, $test->getHue(true));
        $this->assertEquals(100, $test->getSaturation());
        $this->assertEquals(50, $test->getLightness());
    }

    public function testHSLtoRGB()
    {
        // Test HSL to RGB conversion
        $test = new Color();
        $test->setHSLA(270, 50, 25, .7);
        $this->assertEquals([270, 50, 25, .7], $test->getHSLA());
        $this->assertEquals(63.75, $test->getRed());
        $this->assertEquals(31.875, $test->getGreen());
        $this->assertEquals(95.625, $test->getBlue());
    }

    public function testReset()
    {
        // Test if one color space is reset after another one is changed
        $test = new Color();
        $test->setHSLA(270, 50, 25, .7);
        $this->assertEquals(270, $test->getHue());
        $this->assertEquals(50, $test->getSaturation());
        $this->assertEquals(25, $test->getLightness());
        $this->assertEquals([270, 50, 25], $test->getHSL(true));

        $test->setGreen(128);
        $this->assertNotEquals(270, $test->getHue());
        $this->assertNotEquals(50, $test->getSaturation());
        $this->assertNotEquals(25, $test->getLightness());
        $this->assertNotEquals([270, 50, 25], $test->getHSL(true));

        $this->assertEquals([63.75, 128, 95.625], $test->getRGB());
        $this->assertEquals([64, 128, 96], $test->getRGB(true));

        $test->setHue(210); // changes only green and blue
        $this->assertEquals(63.75, $test->getRed());
        $this->assertNotEquals(128, $test->getGreen());
        $this->assertNotEquals(95.625, $test->getBlue());
        $this->assertNotEquals([64, 128, 96], $test->getRGB(true));

        $test->setSaturation(75);
        $this->assertNotEquals(63.75, $test->getRed());
    }
}
