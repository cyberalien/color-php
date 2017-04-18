<?php

/**
 * This file is part of the color package.
 *
 * (c) Vjacheslav Trushkin <cyberalien@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package cyberalien/color
 */

namespace CyberAlien\Color;

/*
 * Library was written in 2 languages to make it usable in both client side
 * web components and server side scripts.
 *
 * JavaScript version is available at @cyberalien/color NPM package and is
 * usable in in browser without any dependencies and in Node.js.
 *
 * Unusual coding style was used to make code consistent between different languages:
 *
 * Names for protected properties start with _ for consistency with
 * JavaScript version because ES6 does not support functions visibility.
 *
 * Functions that have no return value return $this for method chaining.
 */

/**
 * Color class.
 *
 * You can set and get color or components in HSL and RGB color spaces,
 * import/export color from/to different commonly used formats, mix colors.
 * Class automatically converts between color spaces when needed.
 */
class Color
{
    /**
     * @var array|null Value in RGB color space
     *
     * @ignore
     */
    protected $_rgb = null;

    /**
     * @var array|null Value in HSL color space
     *
     * @ignore
     */
    protected $_hsl = null;

    /**
     * @var array|null Value in RGB color space, rounded
     *
     * @ignore
     */
    protected $_rgbRounded = null;

    /**
     * @var array|null Value in HSL color space, rounded
     *
     * @ignore
     */
    protected $_hslRounded = null;

    /**
     * @var float
     *
     * @ignore
     */
    protected $_alpha = 1;

    /**
     * @var float
     *
     * @ignore
     */
    protected $_luminance = null;

    /*
     * Set values
     */

    /**
     * Set value in RGB color space.
     * Setting RGB color resets alpha to 1
     *
     * @param float $red Red color component in 0-255 range
     * @param float $green Green color component in 0-255 range
     * @param float $blue Blue color component in 0-255 range
     * @param boolean $rounded (optional) True if values are rounded.
     *  Used for performance to avoid rounding values when its not needed.
     *
     * @return Color Current color instance for method chaining
     */
    public function setRGB($red, $green, $blue, $rounded = false)
    {
        return $this->setRGBArray(array($red, $green, $blue, 1), $rounded);
    }

    /**
     * Set value in RGB color space with alpha channel.
     *
     * @param float $red Red color component in 0-255 range
     * @param float $green Green color component in 0-255 range
     * @param float $blue Blue color component in 0-255 range
     * @param float $alpha Alpha in 0-1 range
     * @param boolean $rounded (optional) True if values are rounded.
     *  Used for performance to avoid rounding values when its not needed.
     *
     * @return Color Current color instance for method chaining
     */
    public function setRGBA($red, $green, $blue, $alpha, $rounded = false)
    {
        return $this->setRGBArray(array($red, $green, $blue, $alpha), $rounded);
    }

    /**
     * Set value as RGB array.
     * If alpha channel is missing, alpha will be reset to 1
     *
     * @param array $color Array of color components
     * @param boolean $rounded (optional) True if values are rounded.
     *  Used for performance to avoid rounding values when its not needed.
     *
     * @return Color|null Current color instance for method chaining, null on failure
     */
    public function setRGBArray($color, $rounded = false)
    {
        if (count($color) === 4) {
            $this->_alpha = array_pop($color);
        } elseif (count($color) !== 3) {
            return null;
        } else {
            $this->_alpha = 1;
        }

        $this->_resetSpaces('rgb');
        $this->_rgb = $color;
        $this->_rgbRounded = $rounded ? $color : null;

        return $this;
    }

    /**
     * Set value in HSL color space.
     * Setting HSL color resets alpha to 1
     *
     * @param float $hue Hue color component in 0-360 range
     * @param float $saturation Saturation color component in 0-100 range
     * @param float $lightness Lightness color component in 0-100 range
     * @param boolean $rounded (optional) True if values are rounded
     *
     * @return Color Current color instance for method chaining
     */
    public function setHSL($hue, $saturation, $lightness, $rounded = false)
    {
        return $this->setHSLArray(array($hue, $saturation, $lightness, 1), $rounded);
    }

    /**
     * Set value in HSL color space with alpha channel.
     *
     * @param float $hue Hue color component in 0-360 range
     * @param float $saturation Saturation color component in 0-100 range
     * @param float $lightness Lightness color component in 0-100 range
     * @param float $alpha Alpha in 0-1 range
     * @param boolean $rounded (optional) True if values are rounded
     *
     * @return Color Current color instance for method chaining
     */
    public function setHSLA($hue, $saturation, $lightness, $alpha, $rounded = false)
    {
        return $this->setHSLArray(array($hue, $saturation, $lightness, $alpha), $rounded);
    }

    /**
     * Set value as HSL array.
     * If alpha channel is missing, alpha will be reset to 1
     *
     * @param array $color Array of color components
     * @param boolean $rounded (optional) True if values are rounded
     *
     * @return Color|null Current color instance for method chaining, null on failure
     */
    public function setHSLArray($color, $rounded = false)
    {
        if (count($color) === 4) {
            $this->_alpha = array_pop($color);
        } elseif (count($color) !== 3) {
            return null;
        } else {
            $this->_alpha = 1;
        }

        $this->_resetSpaces('hsl');
        $this->_hsl = $color;
        $this->_hslRounded = $rounded ? $color : null;

        return $this;
    }

    /**
     * Set alpha channel value
     *
     * @param float $value Alpha value in 0-1 range
     *
     * @return Color Current color instance for method chaining
     */
    public function setAlpha($value)
    {
        $this->_alpha = $value;
        return $this;
    }

    /**
     * Set red color component
     *
     * @param float $value Red color component in 0-255 range
     * @param boolean $rounded (optional) True if value is rounded
     *
     * @return Color Current color instance for method chaining
     */
    public function setRed($value, $rounded = false)
    {
        return $this->_setRGB(0, $value, $rounded);
    }

    /**
     * Set green color component
     *
     * @param float $value Green color component in 0-255 range
     * @param boolean $rounded (optional) True if value is rounded
     *
     * @return Color Current color instance for method chaining
     */
    public function setGreen($value, $rounded = false)
    {
        return $this->_setRGB(1, $value, $rounded);
    }

    /**
     * Set blue color component
     *
     * @param float $value Blue color component in 0-255 range
     * @param boolean $rounded (optional) True if value is rounded
     *
     * @return Color Current color instance for method chaining
     */
    public function setBlue($value, $rounded = false)
    {
        return $this->_setRGB(2, $value, $rounded);
    }

    /**
     * Set hue color component
     *
     * @param float $value Hue color component in 0-360 range
     * @param boolean $rounded (optional) True if value is rounded
     *
     * @return Color Current color instance for method chaining
     */
    public function setHue($value, $rounded = false)
    {
        return $this->_setHSL(0, $value, $rounded);
    }

    /**
     * Set saturation color component
     *
     * @param float $value Saturation color component in 0-100 range
     * @param boolean $rounded (optional) True if value is rounded
     *
     * @return Color Current color instance for method chaining
     */
    public function setSaturation($value, $rounded = false)
    {
        return $this->_setHSL(1, $value, $rounded);
    }

    /**
     * Set lightness color component
     *
     * @param float $value Lightness color component in 0-360 range
     * @param boolean $rounded (optional) True if value is rounded
     *
     * @return Color Current color instance for method chaining
     */
    public function setLightness($value, $rounded = false)
    {
        return $this->_setHSL(2, $value, $rounded);
    }

    /**
     * Mix with another color
     *
     * @param Color $color Color to mix this color with
     * @param float $weight (optional) Percentage of mixed color to be included in mix
     *
     * @return Color Current color instance for method chaining
     */
    public function mix($color, $weight = 50.0) {
        if ($weight <= 0) {
            return $this;
        }
        if ($weight >= 100) {
            $this->reset();
            $this->setRGBArray($color->getRGBA());
            return $this;
        }

        if ($this->_rgb === null) {
            $this->_convertToRGB();
        }

        $rgb = $color->getRGBA();
        $mix2 = $weight / 100; // weight of another color
        $mix1 = 1 - $mix2; // weight of this color

        return $this->setRGBArray([
            $this->_rgb[0] * $mix1 + $rgb[0] * $mix2,
            $this->_rgb[1] * $mix1 + $rgb[1] * $mix2,
            $this->_rgb[2] * $mix1 + $rgb[2] * $mix2,
            $this->_alpha * $mix1 + $rgb[3] * $mix2
        ]);
    }

    /*
     * Get values
     */

    /**
     * Get RGB value as array
     *
     * @param boolean $round (optional) True if result should be rounded
     *
     * @return array Array of color values
     */
    public function getRGB($round = false)
    {
        if ($this->_rgb === null) {
            $this->_convertToRGB();
        }
        if ($round && $this->_rgbRounded === null) {
            $this->_roundRGB();
        }

        return $round ? $this->_rgbRounded : $this->_rgb;
    }

    /**
     * Get RGB value as array with alpha channel
     *
     * @param boolean $round (optional) True if result should be rounded
     *
     * @return array Array of color values
     */
    public function getRGBA($round = false)
    {
        $result = $this->getRGB($round);
        $result[] = $this->_alpha;
        return $result;
    }

    /**
     * Get HSL value as array
     *
     * @param boolean $round (optional) True if result should be rounded
     *
     * @return array Array of color values
     */
    public function getHSL($round = false)
    {
        if ($this->_hsl === null) {
            $this->_convertToHSL();
        }
        if ($round && $this->_hslRounded === null) {
            $this->_roundHSL();
        }

        return $round ? $this->_hslRounded : $this->_hsl;
    }

    /**
     * Get HSL value as array with alpha channel
     *
     * @param boolean $round (optional) True if result should be rounded
     *
     * @return array Array of color values
     */
    public function getHSLA($round = false)
    {
        $result = $this->getHSL($round);
        $result[] = $this->_alpha;
        return $result;
    }

    /**
     * Get alpha value
     *
     * @return float Alpha value in 0-1 range
     */
    public function getAlpha()
    {
        return $this->_alpha;
    }

    /**
     * Get red color component
     *
     * @param boolean $round (optional) True if result should be rounded
     *
     * @return float Color component in 0-255 range
     */
    public function getRed($round = false)
    {
        return $this->_getRGB(0, $round);
    }

    /**
     * Get green color component
     *
     * @param boolean $round (optional) True if result should be rounded
     *
     * @return float Color component in 0-255 range
     */
    public function getGreen($round = false)
    {
        return $this->_getRGB(1, $round);
    }

    /**
     * Get blue color component
     *
     * @param boolean $round (optional) True if result should be rounded
     *
     * @return float Color component in 0-255 range
     */
    public function getBlue($round = false)
    {
        return $this->_getRGB(2, $round);
    }

    /**
     * Get hue color component
     *
     * @param boolean $round (optional) True if result should be rounded
     *
     * @return float Hue in 0-360 range
     */
    public function getHue($round = false)
    {
        return $this->_getHSL(0, $round);
    }

    /**
     * Get saturation color component
     *
     * @param boolean $round (optional) True if result should be rounded
     *
     * @return float Saturation in 0-100 range
     */
    public function getSaturation($round = false)
    {
        return $this->_getHSL(1, $round);
    }

    /**
     * Get lightness color component
     *
     * @param boolean $round (optional) True if result should be rounded
     *
     * @return float Lightness in 0-100 range
     */
    public function getLightness($round = false)
    {
        return $this->_getHSL(2, $round);
    }

    /**
     * Get luminance
     *
     * @return float
     */
    public function getLuminance()
    {
        if ($this->_luminance !== null) {
            return $this->_luminance;
        }

        if ($this->_rgb === null) {
            $this->_convertToRGB();
        }

        $values = array();
        for ($i = 0; $i < 3; $i++) {
            $value = $this->_rgb[$i] / 255;
            $values[$i] = $value < .03928 ? $value / 12.92 : pow(($value + .055) / 1.055, 2.4);
        }
        $this->_luminance = $values[0] * .2126 + $values[1] * .7152 + $values[2] * 0.0722;
        return $this->_luminance;
    }

    /**
     * Calculate contrast between this and another color
     *
     * @param Color|float color Color or color's luminance value
     *
     * @returns float Contrast in 1-21 range
     */
    public function getContrast($color) {
        $lum1 = $this->getLuminance() + 0.05;
        $lum2 = (is_numeric($color) ? $color : $color->getLuminance()) + 0.05;

        return $lum1 > $lum2 ? $lum1 / $lum2 : $lum2 / $lum1;
    }

    /*
    * Misc functions
    */

    /**
     * Reset values.
     *
     * @return Color Current color instance for method chaining
     */
    public function reset()
    {
        $this->_rgb = $this->_rgbRounded = $this->_hsl = $this->_hslRounded = $this->_luminance = null;
        $this->_alpha = 1;

        return $this;
    }

    /**
     * Normalize all values.
     *
     * @return Color Current color instance for method chaining
     */
    public function normalize()
    {
        // Normalize RGB color space
        if ($this->_rgb !== null) {
            $this->_rgb = array(
                $this->_rgb[0] < 0 ? 0 : ($this->_rgb[0] > 255 ? 255 : $this->_rgb[0]),
                $this->_rgb[1] < 0 ? 0 : ($this->_rgb[1] > 255 ? 255 : $this->_rgb[1]),
                $this->_rgb[2] < 0 ? 0 : ($this->_rgb[2] > 255 ? 255 : $this->_rgb[2])
            );
            if ($this->_rgbRounded !== null) {
                $this->_rgbRounded = array(
                    $this->_rgbRounded[0] < 0 ? 0 : ($this->_rgbRounded[0] > 255 ? 255 : $this->_rgbRounded[0]),
                    $this->_rgbRounded[1] < 0 ? 0 : ($this->_rgbRounded[1] > 255 ? 255 : $this->_rgbRounded[1]),
                    $this->_rgbRounded[2] < 0 ? 0 : ($this->_rgbRounded[2] > 255 ? 255 : $this->_rgbRounded[2])
                );
            }
        }

        // Normalize HSL color space
        if ($this->_hsl !== null) {
            $this->_hsl = array(
                $this->_hsl[0] < 0 ? $this->_hsl[0] % 360 + 360 : ($this->_hsl[0] >= 360 ? $this->_hsl[0] % 360 : $this->_hsl[0]),
                $this->_hsl[1] < 0 ? 0 : ($this->_hsl[1] > 100 ? 100 : $this->_hsl[1]),
                $this->_hsl[2] < 0 ? 0 : ($this->_hsl[2] > 100 ? 100 : $this->_hsl[2]),
            );
            if ($this->_hslRounded !== null) {
                $this->_hslRounded = array(
                    $this->_hslRounded[0] < 0 ? $this->_hslRounded[0] % 360 + 360 : ($this->_hslRounded[0] >= 360 ? $this->_hslRounded[0] % 360 : $this->_hslRounded[0]),
                    $this->_hslRounded[1] < 0 ? 0 : ($this->_hslRounded[1] > 100 ? 100 : $this->_hslRounded[1]),
                    $this->_hslRounded[2] < 0 ? 0 : ($this->_hslRounded[2] > 100 ? 100 : $this->_hslRounded[2]),
                );
            }
        }

        // Normalize alpha
        $this->_alpha = $this->_alpha < 0 ? 0 : ($this->_alpha > 1 ? 1 : $this->_alpha);

        // Reset luminance cache
        $this->_luminance = null;

        return $this;
    }

    /**
     * Convert color to color keyword
     *
     * @param boolean $findClosest (optional) True if function should find closest keyword, false if exact match is required
     * @param boolean $useExtended (optional) True if extended keywords list should be used
     *
     * @return string|false Keyword, false on error
     */
    public function toKeyword($findClosest = true, $useExtended = true)
    {
        // Check for transparent color
        if ($this->_alpha === 0) {
            return 'transparent';
        }

        // Get keywords and rgb color
        if ($useExtended) {
            $keywords = Keywords::$all;
        } else {
            $keywords = Keywords::$base;
        }

        if ($this->_rgb === null) {
            $this->_convertToRGB();
        }
        $color = $this->_rgb;

        $match = false;
        $margin = $findClosest ? 1000 : 1;
        $componentMargin = $findClosest ? 256 : 1;

        // Check each component
        foreach ($keywords as $keyword => $rgb) {
            $diff = 0;
            $maxComponentDiff = 0;
            for ($i = 0; $i < 3; $i++) {
                $componentDiff = abs($rgb[$i] - $color[$i]);
                $diff += $componentDiff;
                if ($diff > $margin) {
                    break;
                }
                $maxComponentDiff = max($maxComponentDiff, $componentDiff);
            }

            // Check for exact match
            if ($diff == 0) {
                return $keyword;
            }

            // Compare to previous results
            if ($findClosest && $diff < $margin) {
                $match = $keyword;
                $margin = $diff;
                $componentMargin = $maxComponentDiff;
            } elseif ($findClosest && $diff == $margin && $maxComponentDiff < $componentMargin) {
                // Same overall difference as previously found match, but each component difference is smaller
                $match = $keyword;
                $componentMargin = $maxComponentDiff;
            }
        }

        return $match;
    }

    /**
     * Get hex string
     *
     * @param bool $compress True if color should be compressed (such as #123 instead of #112233)
     *
     * @return string
     */
    public function toHex($compress = false)
    {
        return $this->_getHexValue($compress, false);
    }

    /**
     * Get hex string with alpha channel
     *
     * @param bool $compress True if color should be compressed (such as #f123 instead of #FF112233)
     *
     * @return string
     */
    public function toIEHex($compress = false)
    {
        return $this->_getHexValue($compress, true);
    }

    /**
     * Get color as rgb or rgba string
     *
     * @param bool $compress True if string should be compressed
     * @param bool $ignoreAlpha True if alpha channel should be ignored. Returns rgb() string
     * @param int $alphaPrecision Number of digits after dot in alpha. Default = 2
     *
     * @return string
     */
    public function toRGBString($compress = false, $ignoreAlpha = false, $alphaPrecision = 2)
    {
        return $this->toString(array(
            'format' => 'rgb',
            'compress' => $compress,
            'ignoreAlpha' => $ignoreAlpha,
            'alphaPrecision' => $alphaPrecision
        ));
    }

    /**
     * Get hsl/hsla string
     *
     * @param bool $compress True if string should be compressed
     * @param bool $ignoreAlpha True if alpha channel should be ignored. Returns hsl() string
     * @param int $alphaPrecision Number of digits after dot in alpha. Default = 2
     * @param int $roundPrecision Number of digits after dot in color components. Default = 2
     *
     * @return string
     */
    public function toHSLString($compress = false, $ignoreAlpha = false, $alphaPrecision = 2, $roundPrecision = 2)
    {
        return $this->toString(array(
            'format' => 'hsl',
            'compress' => $compress,
            'ignoreAlpha' => $ignoreAlpha,
            'alphaPrecision' => $alphaPrecision,
            'roundPrecision' => $roundPrecision
        ));
    }

    /**
     * Get value as string
     *
     * @param array $options Array of options. Possible options:
     *  format: color format. possible values:
     *      auto (default): set automatically.
     *          If RGB color is rounded or roundPrecision is set to 0, result color
     *          will be in hex (if alpha == 1 or ignored) or rgba format. Otherwise
     *          result will be in hsl or hsla format.
     *      rgb: rgb(r, g, b) or rgba(r, g, b, a)
     *      rgba: rgba(r, g, b, a)
     *      hsl: hsl(h, s, l) or hsla(h, s, l, a)
     *      hsla: hsla(h, s, l, a)
     *      hex: hex string
     *      iehex: hex string with alpha channel
     *  ignoreAlpha: true if alpha channel should be ignored. default = false
     *      This option is ignored when format is set to 'hex' or 'iehex'
     *  roundPrecision: number of digits after dot in floating numbers. default = 2
     *      Floating numbers are allowed only in hsl() and hsla() colors
     *  alphaPrecision: number of digits after dot in alpha channel. default = 2
     *      If set to 0 alpha channel is ignored. This option is ignored if alpha value
     *      is 1 or ignoreAlpha is set or if selected format doesn't support alpha channel.
     *  compress: true if result string should be as short as possible. Examples:
     *      compressed:
     *          rgba(1,2,3,.4)
     *          #123
     *      not compressed:
     *          rgba(1, 2, 3, 0.4)
     *          #112233
     *
     * @return string
     */
    public function toString($options = array())
    {
        $format = isset($options['format']) ? $options['format'] : 'auto';
        $ignoreAlpha = isset($options['ignoreAlpha']) ? $options['ignoreAlpha'] : false;
        $roundPrecision = isset($options['roundPrecision']) ? $options['roundPrecision'] : 2;
        $alphaPrecision = isset($options['alphaPrecision']) ? $options['alphaPrecision'] : 2;
        $compress = isset($options['compress']) ? $options['compress'] : false;

        $alpha = $ignoreAlpha ? 1 : $this->_round($this->_alpha, $alphaPrecision);
        $comma = $compress ? ',' : ', ';

        switch ($format) {
            case 'auto':
                // Try hex or rgba format
                if ($this->_rgb === null) {
                    $this->_convertToRGB();
                }
                if ($this->_rgbRounded === null) {
                    $this->_roundRGB();
                }

                // Check if components are rounded
                if ($roundPrecision > 0 && !$this->_equalColors($this->_rgb, $this->_rgbRounded)) {
                    $format = 'hsl';
                    break;
                }

                // Rounded or precision == 0
                if ($alpha == 1) {
                    return $this->_getHexValue($compress, false);
                }
                $result = 'rgba(' .
                    $this->_rgbRounded[0] . $comma .
                    $this->_rgbRounded[1] . $comma .
                    $this->_rgbRounded[2] . $comma .
                    $alpha . ')';
                return $compress ? $this->_compressString($result) : $result;

            case 'rgb':
            case 'rgba':
                if ($this->_rgb === null) {
                    $this->_convertToRGB();
                }
                if ($this->_rgbRounded === null) {
                    $this->_roundRGB();
                }

                $result = ($this->_alpha == 1 && $format !== 'rgba' ? 'rgb(' : 'rgba(') .
                    $this->_rgbRounded[0] . $comma .
                    $this->_rgbRounded[1] . $comma .
                    $this->_rgbRounded[2] .
                    ($alpha == 1 && $format !== 'rgba' ? '' : $comma . $alpha) .
                    ')';
                return $compress ? $this->_compressString($result) : $result;

            case 'hex':
                return $this->_getHexValue($compress, false);

            case 'iehex':
                return $this->_getHexValue($compress, true);
        }

        // Only HSL format left
        if ($this->_hsl === null) {
            $this->_convertToHSL();
        }

        $result = ($alpha == 1 && $format !== 'hsla' ? 'hsl(' : 'hsla(') .
            $this->_round($this->_hsl[0], $roundPrecision) . $comma .
            $this->_round($this->_hsl[1], $roundPrecision) . '%' . $comma .
            $this->_round($this->_hsl[2], $roundPrecision) . '%' .
            ($alpha == 1 && $format !== 'hsla' ? '' : $comma . $alpha) .
            ')';
        return $compress ? $this->_compressString($result) : $result;
    }

    /**
     * Create new color object from keyword
     *
     * @param string $keyword Color value
     * @param boolean $useExtended (optional) True if extended keywords list should be used
     *
     * @return Color|null New color object success, null on failure
     */
    public static function fromKeyword($keyword, $useExtended = true)
    {
        $keyword = strtolower($keyword);

        if ($keyword === 'transparent') {
            $color = new Color();
            return $color->setRGBA(0, 0, 0, 0, true);
        }

        $keywords = $useExtended ? Keywords::$all : Keywords::$base;
        if (!isset($keywords[$keyword])) {
            return null;
        }
        $color = new Color();
        return $color->setRGBArray($keywords[$keyword], true);
    }

    /**
     * Create new color object from HEX string
     *
     * @param string $color Color string
     *
     * @return Color|null New color object success, null on failure
     */
    public static function fromHex($color)
    {
        if (!preg_match('/^#?([\\da-f]+)$/i', $color, $matches)) {
            return null;
        }

        $color = $matches[1];
        $alpha = false;
        $start = 0;

        switch (strlen($color)) {
            /** @noinspection PhpMissingBreakStatementInspection */
            case 4:
                $alpha = substr($color, 0, 1);
                $alpha .= $alpha;
                $start ++;
            // no break

            case 3:
                $red = substr($color, $start, 1);
                $green = substr($color, $start + 1, 1);
                $blue = substr($color, $start + 2, 1);
                $red .= $red;
                $green .= $green;
                $blue .= $blue;
                break;

            /** @noinspection PhpMissingBreakStatementInspection */
            case 8:
                $alpha = substr($color, 0, 2);
                $start += 2;
            // no break

            case 6:
                $red = substr($color, $start, 2);
                $green = substr($color, $start + 2, 2);
                $blue = substr($color, $start + 4, 2);
                break;

            default:
                return null;
        }

        $color = new Color();
        return $color->setRGBA(hexdec($red), hexdec($green), hexdec($blue), $alpha === false ? 1 : hexdec($alpha) / 255, true);
    }

    /**
     * Create new color object from string
     *
     * @param string $color Color string
     *
     * @return Color|null New color object success, null on failure
     */
    public static function fromString($color)
    {
        if (strpos($color, '(') === false) {
            // Missing required character. Test for hex string and keyword
            $result = self::fromHex($color);
            return $result === null ? self::fromKeyword($color) : $result;
        }

        // Remove whitespace and change to lower case
        $color = preg_replace('/\s+/', '', strtolower($color));
        if (substr($color, -1) !== ')') {
            return null;
        }

        $parts = explode('(', substr($color, 0, strlen($color) - 1));
        if (count($parts) !== 2 || preg_match('/[^\\d.,%-]/', $parts[1])) {
            return null;
        }

        $keyword = $parts[0];
        $colors = explode(',', $parts[1]);
        $alpha = 1;

        if (substr($keyword, -1) === 'a') {
            // with alpha
            if (count($colors) !== 4) {
                return null;
            }
            $alpha = floatval(array_pop($colors));
            $alpha = $alpha < 0 ? 0 : ($alpha > 1 ? 1 : $alpha);
        } elseif (count($colors) !== 3) {
            return null;
        }

        switch ($keyword) {
            case 'rgb':
            case 'rgba':
                if (substr($colors[0], -1) === '%') {
                    // All components must be percentages
                    if (substr($colors[1], -1) !== '%' || substr($colors[2], -1) !== '%') {
                        return null;
                    }

                    // Convert to numbers and normalize colors
                    $r = floatval($colors[0]) * 2.55;
                    $g = floatval($colors[1]) * 2.55;
                    $b = floatval($colors[2]) * 2.55;

                    $color = new Color();
                    return $color->setRGBA(
                        $r < 0 ? 0 : ($r > 255 ? 255 : $r),
                        $g < 0 ? 0 : ($g > 255 ? 255 : $g),
                        $b < 0 ? 0 : ($b > 255 ? 255 : $b),
                        $alpha
                    );
                }

                // None of components must be percentages
                if (strpos($parts[1], '%') !== false) {
                    return null;
                }

                // Double values are not allowed in rgb()
                $r = intval($colors[0]);
                $g = intval($colors[1]);
                $b = intval($colors[2]);

                $color = new Color();
                return $color->setRGBA(
                    $r < 0 ? 0 : ($r > 255 ? 255 : $r),
                    $g < 0 ? 0 : ($g > 255 ? 255 : $g),
                    $b < 0 ? 0 : ($b > 255 ? 255 : $b),
                    $alpha,
                    true
                );

            case 'hsl':
            case 'hsla':
                if (strpos($colors[0], '%') !== false || substr($colors[1], -1) !== '%' || substr($colors[2], -1) !== '%') {
                    // Hue cannot be percentage, saturation and lightness must be percentage
                    return null;
                }

                // Convert to float numbers
                $h = floatval($colors[0]);
                $s = floatval($colors[1]);
                $l = floatval($colors[2]);
                $rounded = strpos($parts[1], '.') === false;

                // Create new object, assign normalized values and return color
                $color = new Color();
                return $color->setHSLA(
                    $h < 0 ? $h % 360 + 360 : ($h >= 360 ? $h % 360 : $h),
                    $s < 0 ? 0 : ($s > 100 ? 100 : $s),
                    $l < 0 ? 0 : ($l > 100 ? 100 : $l),
                    $alpha,
                    $rounded
                );
        }

        return null;
    }

    /*
     * Protected functions
     *
     * Names for protected functions start with _ for consistency with
     * JavaScript version because ES6 does not support functions visibility
     */

    /**
     * Internal function used by setRed() and similar functions
     *
     * @param int $index
     * @param float $value
     * @param boolean $rounded
     *
     * @return Color
     *
     * @ignore
     */
    protected function _setRGB($index, $value, $rounded)
    {
        if ($this->_rgb === null) {
            $this->_convertToRGB();
        }

        // Set value
        $this->_resetSpaces('rgb');
        $this->_rgb[$index] = $value;

        // Set rounded value
        if ($this->_rgbRounded !== null) {
            if ($rounded || intval($value) === $value) {
                $this->_rgbRounded[$index] = $value;
            } else {
                $this->_rgbRounded = null;
            }
        }

        return $this;
    }


    /**
     * Internal function used by setHue() and similar functions
     *
     * @param int $index
     * @param float $value
     * @param boolean $rounded
     *
     * @return Color
     *
     * @ignore
     */
    protected function _setHSL($index, $value, $rounded)
    {
        if ($this->_hsl === null) {
            $this->_convertToHSL();
        }

        // Set value
        $this->_resetSpaces('hsl');
        $this->_hsl[$index] = $value;

        // Set rounded value
        if ($this->_hslRounded !== null) {
            if ($rounded || intval($value) === $value) {
                $this->_hslRounded[$index] = $value;
            } else {
                $this->_hslRounded = null;
            }
        }

        return $this;
    }

    /**
     * Internal function used by getRed() and similar functions
     *
     * @param int $index
     * @param boolean $round
     *
     * @return float
     *
     * @ignore
     */
    protected function _getRGB($index, $round)
    {
        if ($this->_rgb === null) {
            $this->_convertToRGB();
        }
        if ($round && $this->_rgbRounded === null) {
            $this->_roundRGB();
        }
        return $round ? $this->_rgbRounded[$index] : $this->_rgb[$index];
    }

    /**
     * Internal function used by getHue() and similar functions
     *
     * @param $index
     * @param $round
     *
     * @return float
     *
     * @ignore
     */
    protected function _getHSL($index, $round)
    {
        if ($this->_hsl === null) {
            $this->_convertToHSL();
        }
        if ($round && $this->_hslRounded === null) {
            $this->_roundHSL();
        }
        return $round ? $this->_hslRounded[$index] : $this->_hsl[$index];
    }

    /**
     * Round RGB colors
     *
     * @ignore
     */
    protected function _roundRGB()
    {
        $r = round($this->_rgb[0]);
        $g = round($this->_rgb[1]);
        $b = round($this->_rgb[2]);

        // Normalize values
        $this->_rgbRounded = [
            $r < 0 ? 0 : ($r > 255 ? 255 : $r),
            $g < 0 ? 0 : ($g > 255 ? 255 : $g),
            $b < 0 ? 0 : ($b > 255 ? 255 : $b)
        ];
    }

    /**
     * Round HSL colors
     *
     * @ignore
     */
    protected function _roundHSL()
    {
        $h = round($this->_hsl[0]);
        $s = round($this->_hsl[1]);
        $l = round($this->_hsl[2]);

        // Normalize values
        $this->_hslRounded = [
            $h < 0 ? $h % 360 + 360 : ($h >= 360 ? $h % 360 : $h),
            $s < 0 ? 0 : ($s > 100 ? 100 : $s),
            $l < 0 ? 0 : ($l > 100 ? 100 : $l)
        ];
    }

    /**
     * Normalize values
     *
     * @param float $value Value to normalize
     * @param float $max (optional) Maximum value
     *
     * @return float Normalized value
     *
     * @ignore
     */
    protected function _normalize($value, $max)
    {
        return $value < 0 ? 0 : ($value > $max ? $max : $value);
    }

    /**
     * Normalize hue value
     *
     * @param float $value Hue to normalize
     *
     * @return float Normalized hue
     *
     * @ignore
     */
    protected function _normalizeHue($value)
    {
        return $value < 0 ? $value % 360 + 360 : ($value >= 360 ? $value % 360 : $value);
    }

    /**
     * Internal function used by _hsl2rgb()
     *
     * @param $n1
     * @param $n2
     * @param $hue
     *
     * @return mixed
     *
     * @ignore
     */
    private function _valore($n1, $n2, $hue)
    {
        // Normalize hue
        $hue = $hue < 0 ? $hue % 360 + 360 : ($hue >= 360 ? $hue % 360 : $hue);

        if ($hue >= 240) {
            $result = $n1;
        } elseif ($hue >= 180) {
            $result = $n1 + ($n2 - $n1) * (240 - $hue) / 60;
        } elseif ($hue >= 60) {
            $result = $n2;
        } else {
            $result = $n1 + ($n2 - $n1) * $hue / 60;
        }

        return $result;
    }

    /**
     * Convert HSL color to RGB
     *
     * @param float $r Red color component in 0-255 range
     * @param float $g Green color component in 0-255 range
     * @param float $b Blue color component in 0-255 range
     * @param boolean $round (optional) True if result should be rounded
     *
     * @return array HSL color. Hue is in 0-360 range, other color components are in 0-100 range
     *
     * @ignore
     */
    protected function _rgb2hsl($r, $g, $b, $round = false)
    {
        $c1 = $r / 255;
        $c2 = $g / 255;
        $c3 = $b / 255;

        $kmin = min($c1, $c2, $c3);
        $kmax = max($c1, $c2, $c3);

        $l = ($kmax + $kmin) / 2;

        if ($kmax == $kmin) {
            $s = 0;
            $h = 0;
        } else {
            if ($l <= 0.5) {
                $s = ($kmax - $kmin) / ($kmax + $kmin);
            } else {
                $s = ($kmax - $kmin) / (2 - $kmax - $kmin);
            }

            $delta = $kmax - $kmin;
            if ($kmax == $c3) {
                $h = 4 + ($c1 - $c2) / $delta;
            } elseif ($kmax == $c2) {
                $h = 2 + ($c3 - $c1) / $delta;
            } else {
                $h = ($c2 - $c3) / $delta;
            }

            $h = $h * 60;

            if ($h < 0) {
                $h += 360;
            }
        }

        $s *= 100;
        $l *= 100;

        return $round ? [round($h), round($s), round($l)] : [$h, $s, $l];
    }

    /**
     * Convert HSL color to RGB
     *
     * @param float $h Hue color component in 0-360 range
     * @param float $s Saturation color component in 0-100 range
     * @param float $l Lightness color component in 0-100 range
     * @param boolean $round (optional) True if result should be rounded
     *
     * @return array RGB color. Each color component is in 0-255 range
     *
     * @ignore
     */
    protected function _hsl2rgb($h, $s, $l, $round = false)
    {
        $hue = $h < 0 ? $h % 360 + 360 : ($h >= 360 ? $h % 360 : $h);
        $sat = $s < 0 ? 0 : ($s > 100 ? 1 : $s / 100);
        $lum = $l < 0 ? 0 : ($l > 100 ? 1 : $l / 100);

        if ($lum <= 0.5) {
            $m2 = $lum * (1 + $sat);
        } else {
            $m2 = $lum + $sat * (1 - $lum);
        }

        $m1 = 2 * $lum - $m2;

        if ($sat == 0 && $hue == 0) {
            $c1 = $lum;
            $c2 = $lum;
            $c3 = $lum;
        } else {
            $c1 = $this->_valore($m1, $m2, $hue + 120);
            $c2 = $this->_valore($m1, $m2, $hue);
            $c3 = $this->_valore($m1, $m2, $hue - 120);
        }

        return [
            $round ? round($c1 * 255) : $c1 * 255,
            $round ? round($c2 * 255) : $c2 * 255,
            $round ? round($c3 * 255) : $c3 * 255,
        ];
    }

    /**
     * Convert color space to RGB
     *
     * @ignore
     */
    protected function _convertToRGB()
    {
        $this->_rgb = $this->_hsl === null ? [255, 0, 0] : $this->_hsl2rgb($this->_hsl[0], $this->_hsl[1], $this->_hsl[2], false);
        $this->_rgbRounded = null;
    }

    /**
     * Convert color space to HSL
     *
     * @ignore
     */
    protected function _convertToHSL()
    {
        $this->_hsl = $this->_rgb === null ? [0, 100, 50] : $this->_rgb2hsl($this->_rgb[0], $this->_rgb[1], $this->_rgb[2], false);
        $this->_hslRounded = null;
    }

    /**
     * Resets color spaces
     *
     * @param string $keep Color space to keep
     *
     * @ignore
     */
    protected function _resetSpaces($keep = '')
    {
        if ($keep !== 'rgb') {
            $this->_rgb = $this->_rgbRounded = null;
        }
        if ($keep !== 'hsl') {
            $this->_hsl = $this->_hslRounded = null;
        }
        $this->_luminance = null;
    }

    /**
     * Round number
     *
     * @param float $number Number to round
     * @param int $precision Number of digits after dot
     *
     * @return float
     *
     * @ignore
     */
    protected function _round($number, $precision)
    {
        $precision = pow(10, $precision);
        return round($number * $precision) / $precision;
    }

    /**
     * Compare color spaces
     *
     * @param array $var1 Array of color components for color 1
     * @param array $var2 Array of color components for color 2
     *
     * @return true if colors are the same
     *
     * @ignore
     */
    protected function _equalColors($var1, $var2)
    {
        if (!is_array($var1) || !is_array($var2)) {
            return false;
        }

        for ($i = 0; $i < 3; $i++) {
            if ($var1[$i] != $var2[$i]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get color as hex string
     *
     * @param $compress True if color should be compressed
     * @param $includeAlpha True if result should include alpha channel (IE format)
     *
     * @return string
     *
     * @ignore
     */
    protected function _getHexValue($compress, $includeAlpha)
    {
        if ($this->_rgb === null) {
            $this->_convertToRGB();
        }
        if ($this->_rgbRounded === null) {
            $this->_roundRGB();
        }

        // Convert to hex string
        $result = '#';
        if ($includeAlpha) {
            $alpha = max(min(round($this->_alpha * 255), 255), 0);
            $result .= ($alpha < 16 ? '0' : '') . dechex($alpha);
        }
        for ($i = 0; $i < 3; $i++) {
            $result .= ($this->_rgbRounded[$i] < 16 ? '0' : '') . dechex($this->_rgbRounded[$i]);
        }

        return $compress ? $this->_compressString($result) : $result;
    }

    /**
     * Compress string
     *
     * @param string $value Color to compress
     *
     * @return string
     *
     * @ignore
     */
    protected function _compressString($value)
    {
        if (substr($value, 0, 1) == '#') {
            // Compress hex string
            $length = strlen($value) - 1;
            if ($length !== 6 && $length !== 8) {
                return $value;
            }
            $str1 = '';
            $str2 = '';
            $total = $length / 2;
            for ($i = 0; $i < $total; $i++) {
                $str1 .= substr($value, $i * 2 + 1, 1);
                $str2 .= substr($value, $i * 2 + 2, 1);
                if ($str1 !== $str2) {
                    return $value;
                }
            }
            return '#' . $str1;
        }

        // Remove extra spaces and zeros
        return strtr($value, [
            ' ' => '',
            '(0.'   => '(.',
            ',0.'   => ',.',
        ]);
    }
}
