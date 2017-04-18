<?php

use \CyberAlien\Color\Color;

class ColorCloneTest extends \PHPUnit\Framework\TestCase
{
    public function testRGBClone()
    {
        $color1 = new color();
        $color1->setRGB(10, 20, 30);

        $color2 = clone $color1;

        // Check clone
        $this->assertEquals([10, 20, 30, 1], $color2->getRGBA());

        // Change original color
        $color1->setRed(15);
        $color1->setAlpha(0.5);
        $this->assertEquals([10, 20, 30, 1], $color2->getRGBA());

        // Change cloned color
        $color2->setGreen(25);
        $color2->setAlpha(0.7);
        $this->assertEquals([15, 20, 30, 0.5], $color1->getRGBA());
    }

    public function testHSLClone()
    {
        $color1 = new color();
        $color1->setHSL(11, 22, 33.5);

        $color2 = clone $color1;

        // Check clone
        $this->assertEquals([11, 22, 33.5, 1], $color2->getHSLA());

        // Change original color
        $color1->setHue(40);
        $color1->setAlpha(0.5);
        $this->assertEquals([11, 22, 33.5, 1], $color2->getHSLA());

        // Change cloned color
        $color2->setLightness(25);
        $color2->setAlpha(0.7);
        $this->assertEquals([40, 22, 33.5, 0.5], $color1->getHSLA());
    }
}
