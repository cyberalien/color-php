<?php

use \CyberAlien\Color\Color;

class ColorConvertersImportHexTest extends \PHPUnit\Framework\TestCase
{
    public function testHEX()
    {
        // 3 characters
        $test = Color::fromHex('1a3');
        $this->assertNotNull($test);
        $this->assertEquals([17, 170, 51, 1], $test->getRGBA());

        // 4 characters
        $test = Color::fromHex('#1a3');
        $this->assertNotNull($test);
        $this->assertEquals([17, 170, 51, 1], $test->getRGBA());

        // 5 characters
        $test = Color::fromHex('21a3B');
        $this->assertNull($test);

        $test = Color::fromHex('#c1a3');
        $this->assertNotNull($test);
        $this->assertEquals([17, 170, 51, 204 / 255], $test->getRGBA());

        // 6 characters
        $test = Color::fromHex('1234F6');
        $this->assertNotNull($test);
        $this->assertEquals([18, 52, 246, 1], $test->getRGBA());

        // 7 characters
        $test = Color::fromHex('#11Aa33');
        $this->assertNotNull($test);
        $this->assertEquals([17, 170, 51, 1], $test->getRGBA());

        // 8 characters
        $test = Color::fromHex('a51234f6');
        $this->assertNotNull($test);
        $this->assertEquals([18, 52, 246, 165 / 255], $test->getRGBA());

        // 9 characters
        $test = Color::fromHex('#a51234f6');
        $this->assertNotNull($test);
        $this->assertEquals([18, 52, 246, 165 / 255], $test->getRGBA());

        // 10+ characters and invalid strings
        $this->assertNull(Color::fromHex('a51234f612'));
        $this->assertNull(Color::fromHex('#a51234f612'));
        $this->assertNull(Color::fromHex('1az'));
        $this->assertNull(Color::fromHex('#12G'));
        $this->assertNull(Color::fromHex('12G521'));
    }
}
