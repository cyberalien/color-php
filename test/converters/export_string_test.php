<?php

use \CyberAlien\Color\Color;

class ColorConvertersExportStringTest extends \PHPUnit\Framework\TestCase
{
    public function testHex()
    {
        // Round values, should get hex string
        $test = new Color();
        $test->setRGB(255, 128, 0);
        $this->assertEquals('#ff8000', $test->toString());
        $this->assertEquals('#ff8000', $test->toString(['compress' => true]));
        $this->assertEquals('#ff8000', $test->toHex(true));

        // Compressed hex string
        $test = new Color();
        $test->setRGB(255, 68, 34);
        $this->assertEquals('#ff4422', $test->toString());
        $this->assertEquals('#f42', $test->toString(['compress' => true]));
        $this->assertEquals('#f42', $test->toHex(true));

        // Force hex string
        $test = new Color();
        $test->setRGBA(255, 68, 34, .7);
        $this->assertEquals('#ff4422', $test->toString(['format' => 'hex']));
        $this->assertEquals('#ff4422', $test->toHex());

        $test = new Color();
        $test->setRGB(255, 64.2, 17);
        $this->assertEquals('#ff4011', $test->toString(['format' => 'hex']));
        $this->assertEquals('#ff4011', $test->toHex());
    }

    public function testRGBA()
    {
        // Round values with alpha, should get rgba color
        $test = new Color();
        $test->setRGBA(128, 68, 34, .7);
        $this->assertEquals('rgba(128, 68, 34, 0.7)', $test->toString());
        $this->assertEquals('rgba(128,68,34,.7)', $test->toString(['compress' => true]));

        // Test with explicit format
        $test = new Color();
        $test->setRGBA(255, 128, 0, .7);
        $this->assertEquals('rgba(255, 128, 0, 0.7)', $test->toString(['format' => 'rgb']));
        $this->assertEquals('rgba(255, 128, 0, 0.7)', $test->toRGBString());
        $this->assertEquals('rgba(255,128,0,.7)', $test->toString(['format' => 'rgb', 'compress' => true]));
        $this->assertEquals('rgba(255,128,0,.7)', $test->toRGBString(true));

        // Test floating numbers
        $test = new Color();
        $test->setRGBA(255, 64.2, 17, .4123);

        // Test with default alphaPrecision = 2
        $this->assertEquals('rgba(255, 64, 17, 0.41)', $test->toString(['format' => 'rgb']));
        $this->assertEquals('rgba(255, 64, 17, 0.41)', $test->toRGBString());
        $this->assertEquals('rgba(255,64,17,.41)', $test->toString(['format' => 'rgb', 'compress' => true]));
        $this->assertEquals('rgba(255,64,17,.41)', $test->toRGBString(true));

        // Test alphaPrecision
        $this->assertEquals('rgba(255, 64, 17, 0.4)', $test->toString(['format' => 'rgb', 'alphaPrecision' => 1]));
        $this->assertEquals('rgba(255, 64, 17, 0.4)', $test->toRGBString(false, false, 1));
        $this->assertEquals('rgba(255, 64, 17, 0.412)', $test->toString(['format' => 'rgb', 'alphaPrecision' => 3]));
        $this->assertEquals('rgba(255, 64, 17, 0.412)', $test->toRGBString(false, false, 3));
        $this->assertEquals('rgba(255, 64, 17, 0.4123)', $test->toString(['format' => 'rgb', 'alphaPrecision' => 5]));
        $this->assertEquals('rgba(255, 64, 17, 0.4123)', $test->toRGBString(false, false, 5));

        // Test that roundPrecision is ignored
        $this->assertEquals('rgba(255, 64, 17, 0.41)', $test->toString(['format' => 'rgb', 'roundPrecision' => 3]));

        // Test with rgba format
        $test = new Color();
        $test->setRGB(255, 128, 0);
        $this->assertEquals('rgba(255, 128, 0, 1)', $test->toString(['format' => 'rgba']));
    }

    public function testRGB()
    {
        $test = new Color();
        $test->setRGB(255, 128, 0);
        $this->assertEquals('rgb(255, 128, 0)', $test->toString(['format' => 'rgb']));
        $this->assertEquals('rgb(255, 128, 0)', $test->toRGBString());
        $this->assertEquals('rgb(255,128,0)', $test->toString(['format' => 'rgb', 'compress' => true]));
        $this->assertEquals('rgb(255,128,0)', $test->toRGBString(true));
    }

    public function testIEHex()
    {
        $test = new Color();
        $test->setRGB(255, 68, 34);
        $this->assertEquals('#ffff4422', $test->toString(['format' => 'iehex']));
        $this->assertEquals('#ffff4422', $test->toIEHex());
        $this->assertEquals('#ff42', $test->toString(['format' => 'iehex', 'compress' => true]));
        $this->assertEquals('#ff42', $test->toIEHex(true));

        $test = new Color();
        $test->setRGBA(255, 128, 0, .7);
        $this->assertEquals('#b3ff8000', $test->toString(['format' => 'iehex']));
        $this->assertEquals('#b3ff8000', $test->toIEHex());
        $this->assertEquals('#b3ff8000', $test->toString(['format' => 'iehex', 'compress' => true]));
        $this->assertEquals('#b3ff8000', $test->toIEHex(true));
    }

    public function testHSL()
    {
        $test = new Color();
        $test->setRGB(255, 37.5, 42.7);
        $this->assertEquals('hsl(358.57, 100%, 57.35%)', $test->toString());
        $this->assertEquals('hsl(358.57, 100%, 57.35%)', $test->toHSLString());
        $this->assertEquals('hsl(358.57,100%,57.35%)', $test->toString(['compress' => true]));
        $this->assertEquals('hsl(358.57,100%,57.35%)', $test->toHSLString(true));
        $this->assertEquals('hsla(358.57, 100%, 57.35%, 1)', $test->toString(['format' => 'hsla']));

        // Test precision
        $this->assertEquals('hsl(359, 100%, 57%)', $test->toString(['roundPrecision' => 0, 'format' => 'hsl']));
        $this->assertEquals('hsla(359, 100%, 57%, 1)', $test->toString(['roundPrecision' => 0, 'format' => 'hsla']));
        $this->assertEquals('hsl(359, 100%, 57%)', $test->toHSLString(false, false, 2, 0));
        $this->assertEquals('hsl(358.56552, 100%, 57.35294%)', $test->toString(['roundPrecision' => 5]));
        $this->assertEquals('hsl(358.56552, 100%, 57.35294%)', $test->toHSLString(false, false, 2, 5));

        // Test alpha
        $test->setAlpha(.7);
        $this->assertEquals('hsla(358.57, 100%, 57.35%, 0.7)', $test->toString());
        $this->assertEquals('hsla(358.57, 100%, 57.35%, 0.7)', $test->toHSLString());
        $this->assertEquals('hsla(358.57,100%,57.35%,.7)', $test->toString(['compress' => true]));
        $this->assertEquals('hsla(358.57,100%,57.35%,.7)', $test->toHSLString(true));

        // Test forced HSL format
        $test = new Color();
        $test->setRGB(255, 128, 0);
        $this->assertEquals('hsl(30.12, 100%, 50%)', $test->toString(['format' => 'hsl']));
        $this->assertEquals('hsl(30.12, 100%, 50%)', $test->toHSLString());

        $test = new Color();
        $test->setRGBA(128, 68, 34, .7);
        $this->assertEquals('hsla(21.7, 58.02%, 31.76%, 0.7)', $test->toString(['format' => 'hsl']));
        $this->assertEquals('hsla(21.7, 58.02%, 31.76%, 0.7)', $test->toHSLString());
    }
}
