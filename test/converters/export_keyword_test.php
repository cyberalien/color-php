<?php

use \CyberAlien\Color\Color;

class ColorConvertersExportKeywordTest extends \PHPUnit\Framework\TestCase
{
    public function testToKeyword()
    {
        // Test base keyword
        $test = new Color();
        $test->setRGB(0, 0, 255);
        $this->assertEquals('blue', $test->toKeyword());

        // Test extended keywords
        $test = new Color();
        $test->setRGB(255, 250, 250);
        $this->assertEquals('snow', $test->toKeyword());

        // Test closest match
        $test = new Color();
        $test->setRGB(125, 140, 10);
        $this->assertEquals('olive', $test->toKeyword(true, false));

        $test = new Color();
        $test->setRGB(64, 64, 64);
        $this->assertEquals('gray', $test->toKeyword(true, false));

        // Test exact match
        $test = new Color();
        $test->setRGB(0, 0, 128);
        $this->assertEquals('navy', $test->toKeyword(false, false));

        $test = new Color();
        $test->setRGB(250, 128, 114);
        $this->assertEquals('salmon', $test->toKeyword(false));
        $this->assertFalse($test->toKeyword(false, false));

        $test = new Color();
        $test->setRGB(10, 20, 30);
        $this->assertFalse($test->toKeyword(false));

        // Test transparent color
        $test = new Color();
        $test->setRGBA(0, 0, 128, 0);
        $this->assertEquals('transparent', $test->toKeyword());

        $test = new Color();
        $test->setRGBA(255, 128, 64, 0);
        $this->assertEquals('transparent', $test->toKeyword());
    }
}
