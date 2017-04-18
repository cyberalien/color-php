<?php

use \CyberAlien\Color\Color;

class ColorHSLTest extends \PHPUnit\Framework\TestCase
{
    public function testSettingValue()
    {
        // Test setting value and retrieving each component
        $test = new Color();
        $test->setHSL(10, 20, 30);
        $this->assertEquals([10, 20, 30], $test->getHSL());
        $this->assertEquals([10, 20, 30, 1], $test->getHSLA());
        $this->assertEquals(10, $test->getHue());
        $this->assertEquals(20, $test->getSaturation());
        $this->assertEquals(30, $test->getLightness());
        $this->assertEquals(1, $test->getAlpha());
    }

    public function testSetingHSLAValue()
    {
        $test = new Color();
        $test->setHSLA(10, 20, 30, .3);
        $this->assertEquals([10, 20, 30, .3], $test->getHSLA());
    }

    public function testSettingArrayValue()
    {
        // Test setting value as array
        $test = new Color();
        $test->setHSLArray([10, 20, 30, .3]);
        $this->assertEquals([10, 20, 30, .3], $test->getHSLA());
    }

    public function testEachComponent()
    {
        // Test changing each component
        $test = new Color();
        $test->setHSLA(10, 20, 30, .4);

        $test->setHue(200);
        $this->assertEquals([200, 20, 30, .4], $test->getHSLA());

        $test->setSaturation(75);
        $this->assertEquals([200, 75, 30, .4], $test->getHSLA());

        $test->setLightness(25);
        $this->assertEquals([200, 75, 25, .4], $test->getHSLA());

        $test->setAlpha(.7);
        $this->assertEquals([200, 75, 25, .7], $test->getHSLA());
    }

    public function testRoundingValues()
    {
        // Test rounding values and rgb() vs rgba()
        $test = new Color();
        $test->setHSLA(11.5, 25.4, 78.3, .7);

        $this->assertEquals([11.5, 25.4, 78.3], $test->getHSL());
        $this->assertEquals([11.5, 25.4, 78.3, .7], $test->getHSLA());

        $this->assertEquals([12, 25, 78], $test->getHSL(true));
        $this->assertEquals([12, 25, 78, .7], $test->getHSLA(true));

        $this->assertEquals(11.5, $test->getHue());
        $this->assertEquals(12, $test->getHue(true));

        $this->assertEquals(25.4, $test->getSaturation());
        $this->assertEquals(25, $test->getSaturation(true));

        $this->assertEquals(78.3, $test->getLightness());
        $this->assertEquals(78, $test->getLightness(true));
    }

    public function testSettingRoundedValues()
    {
        // Test setting rounded value
        $test = new Color();
        $test->setHSLArray([10.2, 20.9, 30.3, .3]);
        $this->assertEquals([10, 21, 30, .3], $test->getHSLA(true));

        // hsl(true) should return rounded numbers (tested in function above)
        // therefore setting floating values flagged as rounded should return
        // floating values when calling hsl(true)

        // This is incorrect usage of function, but its a good for testing
        $test = new Color();
        $test->setHSLArray([10.2, 20.9, 30.3, .3], true);
        $this->assertEquals([10.2, 20.9, 30.3, .3], $test->getHSLA(true));

        // Setting rounded value without second parameter should not break rounded components
        $test->setLightness(76);
        $this->assertEquals([10.2, 20.9, 76, .3], $test->getHSLA(true));

        // Setting float value without second parameter should break rounded components
        $test->setLightness(56.4);
        $this->assertEquals([10, 21, 56, .3], $test->getHSLA(true));

        // Test each component
        $test->setHue(240.2, true);
        $this->assertEquals(240.2, $test->getHue());
        $this->assertEquals(240.2, $test->getHue(true));

        $test->setSaturation(30.2, true);
        $this->assertEquals(30.2, $test->getSaturation());
        $this->assertEquals(30.2, $test->getSaturation(true));

        $test->setLightness(54.9, true);
        $this->assertEquals(54.9, $test->getLightness());
        $this->assertEquals(54.9, $test->getLightness(true));
    }
}
