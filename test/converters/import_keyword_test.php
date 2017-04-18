<?php

use \CyberAlien\Color\Color;

class ColorConvertersImportKeywordTest extends \PHPUnit\Framework\TestCase
{
    public function testFromKeyword()
    {
        // Test base keyword
        $test = Color::fromKeyword('blue');
        $this->assertNotNull($test);
        $this->assertEquals([0, 0, 255, 1], $test->getRGBA());

        $test = Color::fromKeyword('Red');
        $this->assertNotNull($test);
        $this->assertEquals([255, 0, 0, 1], $test->getRGBA());

        $test = Color::fromKeyword('Red', false);
        $this->assertNotNull($test);

        // Test extended keyword
        $test = Color::fromKeyword('limegreen');
        $this->assertNotNull($test);
        $this->assertEquals([50, 205, 50, 1], $test->getRGBA());

        $test = Color::fromKeyword('SpringGreen');
        $this->assertNotNull($test);
        $this->assertEquals([0, 255, 127, 1], $test->getRGBA());

        $test = Color::fromKeyword('limegreen', false);
        $this->assertNull($test);

        // Test transparent color
        $test = Color::fromKeyword('transparent');
        $this->assertNotNull($test);
        $this->assertEquals([0, 0, 0, 0], $test->getRGBA());
    }

    public function testInvalidKeyword()
    {
        $this->assertNull(Color::fromKeyword('')); // Empty string
        $this->assertNull(Color::fromKeyword('1')); // Invalid string
        $this->assertNull(Color::fromKeyword('Blackest')); // Invalid string
    }
}
