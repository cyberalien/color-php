<?php

use \CyberAlien\Color\Color;

class ColorMixTest extends \PHPUnit\Framework\TestCase
{
    public function testMix()
    {
        $color1 = new Color();
        $color2 = new Color();

        // Test 50% mix
        $color1->setRGB(10, 20, 30);
        $color2->setRGB(0, 40, 60);
        $color1->mix($color2);
        $this->assertEquals([5, 30, 45, 1], $color1->getRGBA());

        // Test 100% mix
        $color1->setRGB(10, 20, 30);
        $color2->setRGB(0, 40, 60);
        $color1->mix($color2, 100);
        $this->assertEquals([0, 40, 60, 1], $color1->getRGBA());

        // Test 0% mix
        $color1->setRGB(10, 20, 30);
        $color2->setRGB(0, 40, 60);
        $color1->mix($color2, 0);
        $this->assertEquals([10, 20, 30, 1], $color1->getRGBA());

        // Test 75% mix
        $color1->setRGB(10, 20, 30);
        $color2->setRGB(0, 40, 60);
        $color1->mix($color2, 75);
        $this->assertEquals([2.5, 35, 52.5, 1], $color1->getRGBA());

        // Test 30% mix
        $color1->setRGB(10, 20, 30);
        $color2->setRGB(0, 40, 60);
        $color1->mix($color2, 30);
        $this->assertEquals([7, 26, 39, 1], $color1->getRGBA());

        // Test with alpha
        $color1->setRGBA(10, 20, 30, .6);
        $color2->setRGBA(0, 40, 60, .2);
        $color1->mix($color2, 75);
        $this->assertEquals([2.5, 35, 52.5, .3], $color1->getRGBA());
    }
}
