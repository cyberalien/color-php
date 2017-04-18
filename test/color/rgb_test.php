<?php

use \CyberAlien\Color\Color;

class ColorRGBTest extends \PHPUnit\Framework\TestCase
{
    public function testSettingValue()
    {
        // Test setting values
        $test = new Color();
        $test->setRGB(10, 20, 30);
        $this->assertEquals([10, 20, 30], $test->getRGB());
        $this->assertEquals([10, 20, 30, 1], $test->getRGBA());
        $this->assertEquals(10, $test->getRed());
        $this->assertEquals(20, $test->getGreen());
        $this->assertEquals(30, $test->getBlue());
        $this->assertEquals(1, $test->getAlpha());
    }

    public function testSettingRGBAValue()
    {
        $test = new Color();
        $test->setRGBA(10, 20, 30, .5);
        $this->assertEquals([10, 20, 30, .5], $test->getRGBA());
    }

    public function testSettingArrayValue()
    {
        // Test setting value as array
        $test = new Color();
        $test->setRGBArray([10, 20, 30, .5]);
        $this->assertEquals([10, 20, 30, .5], $test->getRGBA());
    }

    public function testEachComponent()
    {
        // Test changing each color component
        $test = new Color();
        $test->setRGBA(10, 20, 30, .4);

        $test->setRed(40);
        $this->assertEquals([40, 20, 30, 0.4], $test->getRGBA());

        $test->setGreen(35);
        $this->assertEquals([40, 35, 30, 0.4], $test->getRGBA());

        $test->setBlue(125);
        $this->assertEquals([40, 35, 125, 0.4], $test->getRGBA());

        $test->setAlpha(.3);
        $this->assertEquals([40, 35, 125, 0.3], $test->getRGBA());
    }

    public function testRoundingValues()
    {
        // Test rounding values and rgb() vs rgba()
        $test = new Color();
        $test->setRGBA(11.5, 25.4, 78.3, .7);

        $this->assertEquals([11.5, 25.4, 78.3], $test->getRGB());
        $this->assertEquals([11.5, 25.4, 78.3, .7], $test->getRGBA());

        $this->assertEquals([12, 25, 78], $test->getRGB(true));
        $this->assertEquals([12, 25, 78, .7], $test->getRGBA(true));

        $this->assertEquals(11.5, $test->getRed());
        $this->assertEquals(12, $test->getRed(true));

        $this->assertEquals(25.4, $test->getGreen());
        $this->assertEquals(25, $test->getGreen(true));

        $this->assertEquals(78.3, $test->getBlue());
        $this->assertEquals(78, $test->getBlue(true));
    }

    public function testSettingRoundedValues()
    {
        // Test setting rounded value
        $test = new Color();
        $test->setRGBArray([10.2, 20.9, 30.3, .3]);
        $this->assertEquals([10, 21, 30, .3], $test->getRGBA(true));

        // rgb(true) should return rounded numbers (tested in function above)
        // therefore setting floating values flagged as rounded should return
        // floating values when calling rgb(true)

        // This is incorrect usage of function, but its a good for testing
        $test = new Color();
        $test->setRGBArray([10.2, 20.9, 30.3, .3], true);
        $this->assertEquals([10.2, 20.9, 30.3, .3], $test->getRGBA(true));

        // Setting rounded value without second parameter should not break rounded components
        $test->setBlue(76);
        $this->assertEquals([10.2, 20.9, 76, .3], $test->getRGBA(true));

        // Setting float value without second parameter should break rounded components
        $test->setBlue(176.4);
        $this->assertEquals([10, 21, 176, .3], $test->getRGBA(true));

        // Test each component
        $test->setRed(40.2, true);
        $this->assertEquals(40.2, $test->getRed());
        $this->assertEquals(40.2, $test->getRed(true));

        $test->setGreen(30.2, true);
        $this->assertEquals(30.2, $test->getGreen());
        $this->assertEquals(30.2, $test->getGreen(true));

        $test->setBlue(54.9, true);
        $this->assertEquals(54.9, $test->getBlue());
        $this->assertEquals(54.9, $test->getBlue(true));
    }
}
