<?php

use \CyberAlien\Color\Color;

class ColorConvertersExportHexTest extends \PHPUnit\Framework\TestCase
{
    public function testHEX()
    {
        // Test with color that can be compressed
        $test = new Color();
        $test->setRGBA(17, 170, 51, 204 / 255);
        $this->assertEquals('#11aa33', $test->toHex());
        $this->assertEquals('#1a3', $test->toHex(true));
        $this->assertEquals('#cc11aa33', $test->toIEHex());
        $this->assertEquals('#c1a3', $test->toIEHex(true));

        // Change alpha to something that cannot be compressed
        $test->setAlpha(.25);
        $this->assertEquals('#1a3', $test->toHex(true));
        $this->assertEquals('#4011aa33', $test->toIEHex());
        $this->assertEquals('#4011aa33', $test->toIEHex(true));

        // Change component to something that cannot be compressed
        $test->setRed(100);
        $this->assertEquals('#64aa33', $test->toHex(true));

        // Test HSL conversion
        $test->setHSLA(270, 50, 25, .7);
        $this->assertEquals('#402060', $test->toHex());
        $this->assertEquals('#b3402060', $test->toIEHex());
    }
}
