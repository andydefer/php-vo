<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Enums;

/**
 * Enum representing standard colors following CSS Color Module Level 4 (W3C).
 *
 * @see https://www.w3.org/TR/css-color-4/#named-colors
 *
 * @example
 * // Basic usage
 * $color = Color::RED;
 * echo $color->getHex(); // "#FF0000"
 * echo $color->getRgb(); // "rgb(255, 0, 0)"
 * echo $color->getHsl(); // "hsl(0, 100%, 50%)"
 *
 * // Get color components
 * [$r, $g, $b] = $color->getRgbComponents(); // [255, 0, 0]
 *
 * // Check color properties
 * if ($color->isDark()) { ... }
 * if ($color->isWarm()) { ... }
 *
 * // Find color by hex
 * $color = Color::tryFromHex('#FF0000'); // Color::RED
 */
enum Color: string
{
    // Reds
    case RED = 'red';
    case DARK_RED = 'darkred';
    case FIREBRICK = 'firebrick';
    case INDIAN_RED = 'indianred';
    case LIGHT_CORAL = 'lightcoral';
    case SALMON = 'salmon';
    case DARK_SALMON = 'darksalmon';
    case LIGHT_SALMON = 'lightsalmon';
    case CRIMSON = 'crimson';

    // Pinks
    case PINK = 'pink';
    case LIGHT_PINK = 'lightpink';
    case HOT_PINK = 'hotpink';
    case DEEP_PINK = 'deeppink';
    case PALE_VIOLET_RED = 'palevioletred';
    case MEDIUM_VIOLET_RED = 'mediumvioletred';

    // Oranges
    case ORANGE = 'orange';
    case DARK_ORANGE = 'darkorange';
    case CORAL = 'coral';
    case TOMATO = 'tomato';
    case ORANGE_RED = 'orangered';
    case GOLD = 'gold';

    // Yellows
    case YELLOW = 'yellow';
    case LIGHT_YELLOW = 'lightyellow';
    case LEMON_CHIFFON = 'lemonchiffon';
    case LIGHT_GOLDENROD_YELLOW = 'lightgoldenrodyellow';
    case PAPAYA_WHIP = 'papayawhip';
    case MOCCASIN = 'moccasin';
    case PEACH_PUFF = 'peachpuff';
    case PALE_GOLDENROD = 'palegoldenrod';
    case KHAKI = 'khaki';
    case DARK_KHAKI = 'darkkhaki';

    // Browns
    case BROWN = 'brown';
    case MAROON = 'maroon';
    case SADDLE_BROWN = 'saddlebrown';
    case SIENNA = 'sienna';
    case CHOCOLATE = 'chocolate';
    case PERU = 'peru';
    case SANDY_BROWN = 'sandybrown';
    case BURLYWOOD = 'burlywood';
    case TAN = 'tan';
    case ROSY_BROWN = 'rosybrown';

    // Greens
    case GREEN = 'green';
    case DARK_GREEN = 'darkgreen';
    case FOREST_GREEN = 'forestgreen';
    case LIME = 'lime';
    case LIME_GREEN = 'limegreen';
    case LIGHT_GREEN = 'lightgreen';
    case PALE_GREEN = 'palegreen';
    case DARK_SEA_GREEN = 'darkseagreen';
    case MEDIUM_SEA_GREEN = 'mediumseagreen';
    case SEA_GREEN = 'seagreen';
    case OLIVE = 'olive';
    case DARK_OLIVE_GREEN = 'darkolivegreen';
    case OLIVE_DRAB = 'olivedrab';
    case YELLOW_GREEN = 'yellowgreen';
    case LAWN_GREEN = 'lawngreen';
    case CHARTREUSE = 'chartreuse';
    case GREEN_YELLOW = 'greenyellow';
    case SPRING_GREEN = 'springgreen';
    case MEDIUM_SPRING_GREEN = 'mediumspringgreen';

    // Cyans
    case CYAN = 'cyan';
    case AQUA = 'aqua';
    case LIGHT_CYAN = 'lightcyan';
    case DARK_CYAN = 'darkcyan';
    case TEAL = 'teal';
    case MEDIUM_TURQUOISE = 'mediumturquoise';
    case TURQUOISE = 'turquoise';
    case DARK_TURQUOISE = 'darkturquoise';
    case POWDER_BLUE = 'powderblue';
    case CADET_BLUE = 'cadetblue';

    // Blues
    case BLUE = 'blue';
    case DARK_BLUE = 'darkblue';
    case MIDNIGHT_BLUE = 'midnightblue';
    case NAVY = 'navy';
    case ROYAL_BLUE = 'royalblue';
    case CORNFLOWER_BLUE = 'cornflowerblue';
    case LIGHT_STEEL_BLUE = 'lightsteelblue';
    case LIGHT_SKY_BLUE = 'lightskyblue';
    case SKY_BLUE = 'skyblue';
    case DODGER_BLUE = 'dodgerblue';
    case DEEP_SKY_BLUE = 'deepskyblue';
    case STEEL_BLUE = 'steelblue';
    case MEDIUM_BLUE = 'mediumblue';
    case SLATE_BLUE = 'slateblue';
    case MEDIUM_SLATE_BLUE = 'mediumslateblue';
    case DARK_SLATE_BLUE = 'darkslateblue';

    // Purples
    case PURPLE = 'purple';
    case DARK_PURPLE = 'darkpurple';
    case DARK_MAGENTA = 'darkmagenta';
    case INDIGO = 'indigo';
    case MEDIUM_PURPLE = 'mediumpurple';
    case MEDIUM_ORCHID = 'mediumorchid';
    case ORCHID = 'orchid';
    case PLUM = 'plum';
    case THISTLE = 'thistle';
    case LAVENDER = 'lavender';
    case LAVENDER_BLUSH = 'lavenderblush';

    // Grays
    case GRAY = 'gray';
    case DARK_GRAY = 'darkgray';
    case DIM_GRAY = 'dimgray';
    case LIGHT_GRAY = 'lightgray';
    case SLATE_GRAY = 'slategray';
    case LIGHT_SLATE_GRAY = 'lightslategray';
    case GAINSBORO = 'gainsboro';
    case SILVER = 'silver';
    case WHITE_SMOKE = 'whitesmoke';
    case SNOW = 'snow';
    case GHOST_WHITE = 'ghostwhite';

    // Special
    case BLACK = 'black';
    case WHITE = 'white';
    case TRANSPARENT = 'transparent';

    /**
     * Color hex codes map
     */
    private const HEX_MAP = [
        'red' => '#FF0000',
        'darkred' => '#8B0000',
        'firebrick' => '#B22222',
        'indianred' => '#CD5C5C',
        'lightcoral' => '#F08080',
        'salmon' => '#FA8072',
        'darksalmon' => '#E9967A',
        'lightsalmon' => '#FFA07A',
        'crimson' => '#DC143C',
        'pink' => '#FFC0CB',
        'lightpink' => '#FFB6C1',
        'hotpink' => '#FF69B4',
        'deeppink' => '#FF1493',
        'palevioletred' => '#DB7093',
        'mediumvioletred' => '#C71585',
        'orange' => '#FFA500',
        'darkorange' => '#FF8C00',
        'coral' => '#FF7F50',
        'tomato' => '#FF6347',
        'orangered' => '#FF4500',
        'gold' => '#FFD700',
        'yellow' => '#FFFF00',
        'lightyellow' => '#FFFFE0',
        'lemonchiffon' => '#FFFACD',
        'lightgoldenrodyellow' => '#FAFAD2',
        'papayawhip' => '#FFEFD5',
        'moccasin' => '#FFE4B5',
        'peachpuff' => '#FFDAB9',
        'palegoldenrod' => '#EEE8AA',
        'khaki' => '#F0E68C',
        'darkkhaki' => '#BDB76B',
        'brown' => '#A52A2A',
        'maroon' => '#800000',
        'saddlebrown' => '#8B4513',
        'sienna' => '#A0522D',
        'chocolate' => '#D2691E',
        'peru' => '#CD853F',
        'sandybrown' => '#F4A460',
        'burlywood' => '#DEB887',
        'tan' => '#D2B48C',
        'rosybrown' => '#BC8F8F',
        'green' => '#008000',
        'darkgreen' => '#006400',
        'forestgreen' => '#228B22',
        'lime' => '#00FF00',
        'limegreen' => '#32CD32',
        'lightgreen' => '#90EE90',
        'palegreen' => '#98FB98',
        'darkseagreen' => '#8FBC8F',
        'mediumseagreen' => '#3CB371',
        'seagreen' => '#2E8B57',
        'olive' => '#808000',
        'darkolivegreen' => '#556B2F',
        'olivedrab' => '#6B8E23',
        'yellowgreen' => '#9ACD32',
        'lawngreen' => '#7CFC00',
        'chartreuse' => '#7FFF00',
        'greenyellow' => '#ADFF2F',
        'springgreen' => '#00FF7F',
        'mediumspringgreen' => '#00FA9A',
        'cyan' => '#00FFFF',
        'aqua' => '#00FFFF',
        'lightcyan' => '#E0FFFF',
        'darkcyan' => '#008B8B',
        'teal' => '#008080',
        'mediumturquoise' => '#48D1CC',
        'turquoise' => '#40E0D0',
        'darkturquoise' => '#00CED1',
        'powderblue' => '#B0E0E6',
        'cadetblue' => '#5F9EA0',
        'blue' => '#0000FF',
        'darkblue' => '#00008B',
        'midnightblue' => '#191970',
        'navy' => '#000080',
        'royalblue' => '#4169E1',
        'cornflowerblue' => '#6495ED',
        'lightsteelblue' => '#B0C4DE',
        'lightskyblue' => '#87CEFA',
        'skyblue' => '#87CEEB',
        'dodgerblue' => '#1E90FF',
        'deepskyblue' => '#00BFFF',
        'steelblue' => '#4682B4',
        'mediumblue' => '#0000CD',
        'slateblue' => '#6A5ACD',
        'mediumslateblue' => '#7B68EE',
        'darkslateblue' => '#483D8B',
        'purple' => '#800080',
        'darkpurple' => '#6A0DAD',
        'darkmagenta' => '#8B008B',
        'indigo' => '#4B0082',
        'mediumpurple' => '#9370DB',
        'mediumorchid' => '#BA55D3',
        'orchid' => '#DA70D6',
        'plum' => '#DDA0DD',
        'thistle' => '#D8BFD8',
        'lavender' => '#E6E6FA',
        'lavenderblush' => '#FFF0F5',
        'gray' => '#808080',
        'darkgray' => '#A9A9A9',
        'dimgray' => '#696969',
        'lightgray' => '#D3D3D3',
        'slategray' => '#708090',
        'lightslategray' => '#778899',
        'gainsboro' => '#DCDCDC',
        'silver' => '#C0C0C0',
        'whitesmoke' => '#F5F5F5',
        'snow' => '#FFFAFA',
        'ghostwhite' => '#F8F8FF',
        'black' => '#000000',
        'white' => '#FFFFFF',
        'transparent' => 'transparent',
    ];

    /**
     * RGB values map (for colors that have defined components)
     */
    private const RGB_MAP = [
        'red' => [255, 0, 0],
        'darkred' => [139, 0, 0],
        'firebrick' => [178, 34, 34],
        'indianred' => [205, 92, 92],
        'lightcoral' => [240, 128, 128],
        'salmon' => [250, 128, 114],
        'darksalmon' => [233, 150, 122],
        'lightsalmon' => [255, 160, 122],
        'crimson' => [220, 20, 60],
        'pink' => [255, 192, 203],
        'lightpink' => [255, 182, 193],
        'hotpink' => [255, 105, 180],
        'deeppink' => [255, 20, 147],
        'palevioletred' => [219, 112, 147],
        'mediumvioletred' => [199, 21, 133],
        'orange' => [255, 165, 0],
        'darkorange' => [255, 140, 0],
        'coral' => [255, 127, 80],
        'tomato' => [255, 99, 71],
        'orangered' => [255, 69, 0],
        'gold' => [255, 215, 0],
        'yellow' => [255, 255, 0],
        'lightyellow' => [255, 255, 224],
        'lemonchiffon' => [255, 250, 205],
        'lightgoldenrodyellow' => [250, 250, 210],
        'papayawhip' => [255, 239, 213],
        'moccasin' => [255, 228, 181],
        'peachpuff' => [255, 218, 185],
        'palegoldenrod' => [238, 232, 170],
        'khaki' => [240, 230, 140],
        'darkkhaki' => [189, 183, 107],
        'brown' => [165, 42, 42],
        'maroon' => [128, 0, 0],
        'saddlebrown' => [139, 69, 19],
        'sienna' => [160, 82, 45],
        'chocolate' => [210, 105, 30],
        'peru' => [205, 133, 63],
        'sandybrown' => [244, 164, 96],
        'burlywood' => [222, 184, 135],
        'tan' => [210, 180, 140],
        'rosybrown' => [188, 143, 143],
        'green' => [0, 128, 0],
        'darkgreen' => [0, 100, 0],
        'forestgreen' => [34, 139, 34],
        'lime' => [0, 255, 0],
        'limegreen' => [50, 205, 50],
        'lightgreen' => [144, 238, 144],
        'palegreen' => [152, 251, 152],
        'darkseagreen' => [143, 188, 143],
        'mediumseagreen' => [60, 179, 113],
        'seagreen' => [46, 139, 87],
        'olive' => [128, 128, 0],
        'darkolivegreen' => [85, 107, 47],
        'olivedrab' => [107, 142, 35],
        'yellowgreen' => [154, 205, 50],
        'lawngreen' => [124, 252, 0],
        'chartreuse' => [127, 255, 0],
        'greenyellow' => [173, 255, 47],
        'springgreen' => [0, 255, 127],
        'mediumspringgreen' => [0, 250, 154],
        'cyan' => [0, 255, 255],
        'aqua' => [0, 255, 255],
        'lightcyan' => [224, 255, 255],
        'darkcyan' => [0, 139, 139],
        'teal' => [0, 128, 128],
        'mediumturquoise' => [72, 209, 204],
        'turquoise' => [64, 224, 208],
        'darkturquoise' => [0, 206, 209],
        'powderblue' => [176, 224, 230],
        'cadetblue' => [95, 158, 160],
        'blue' => [0, 0, 255],
        'darkblue' => [0, 0, 139],
        'midnightblue' => [25, 25, 112],
        'navy' => [0, 0, 128],
        'royalblue' => [65, 105, 225],
        'cornflowerblue' => [100, 149, 237],
        'lightsteelblue' => [176, 196, 222],
        'lightskyblue' => [135, 206, 250],
        'skyblue' => [135, 206, 235],
        'dodgerblue' => [30, 144, 255],
        'deepskyblue' => [0, 191, 255],
        'steelblue' => [70, 130, 180],
        'mediumblue' => [0, 0, 205],
        'slateblue' => [106, 90, 205],
        'mediumslateblue' => [123, 104, 238],
        'darkslateblue' => [72, 61, 139],
        'purple' => [128, 0, 128],
        'darkpurple' => [106, 13, 173],
        'darkmagenta' => [139, 0, 139],
        'indigo' => [75, 0, 130],
        'mediumpurple' => [147, 112, 219],
        'mediumorchid' => [186, 85, 211],
        'orchid' => [218, 112, 214],
        'plum' => [221, 160, 221],
        'thistle' => [216, 191, 216],
        'lavender' => [230, 230, 250],
        'lavenderblush' => [255, 240, 245],
        'gray' => [128, 128, 128],
        'darkgray' => [169, 169, 169],
        'dimgray' => [105, 105, 105],
        'lightgray' => [211, 211, 211],
        'slategray' => [112, 128, 144],
        'lightslategray' => [119, 136, 153],
        'gainsboro' => [220, 220, 220],
        'silver' => [192, 192, 192],
        'whitesmoke' => [245, 245, 245],
        'snow' => [255, 250, 250],
        'ghostwhite' => [248, 248, 255],
        'black' => [0, 0, 0],
        'white' => [255, 255, 255],
        'transparent' => null,
    ];

    /**
     * Get the hex color code
     */
    public function getHex(): string
    {
        return self::HEX_MAP[$this->value];
    }

    /**
     * Get RGB string representation
     */
    public function getRgb(): string
    {
        if ($this === self::TRANSPARENT) {
            return 'transparent';
        }
        $rgb = self::RGB_MAP[$this->value];

        return sprintf('rgb(%d, %d, %d)', $rgb[0], $rgb[1], $rgb[2]);
    }

    /**
     * Get RGBA string representation with alpha
     */
    public function getRgba(float $alpha = 1.0): string
    {
        if ($this === self::TRANSPARENT) {
            return 'transparent';
        }
        $rgb = self::RGB_MAP[$this->value];

        return sprintf('rgba(%d, %d, %d, %.2f)', $rgb[0], $rgb[1], $rgb[2], $alpha);
    }

    /**
     * Get RGB components as array
     *
     * @return array{0: int, 1: int, 2: int}|null
     */
    public function getRgbComponents(): ?array
    {
        return self::RGB_MAP[$this->value];
    }

    /**
     * Get HSL string representation
     */
    public function getHsl(): string
    {
        if ($this === self::TRANSPARENT) {
            return 'transparent';
        }
        $rgb = self::RGB_MAP[$this->value];
        [$h, $s, $l] = $this->rgbToHsl($rgb[0], $rgb[1], $rgb[2]);

        return sprintf('hsl(%d, %d%%, %d%%)', round($h), round($s), round($l));
    }

    /**
     * Get HSL components as array
     *
     * @return array{h: float, s: float, l: float}|null
     */
    public function getHslComponents(): ?array
    {
        if ($this === self::TRANSPARENT) {
            return null;
        }
        $rgb = self::RGB_MAP[$this->value];
        [$h, $s, $l] = $this->rgbToHsl($rgb[0], $rgb[1], $rgb[2]);

        return ['h' => $h, 's' => $s, 'l' => $l];
    }

    /**
     * Check if color is dark (useful for text color decisions)
     */
    public function isDark(): bool
    {
        if ($this === self::TRANSPARENT) {
            return false;
        }
        $rgb = self::RGB_MAP[$this->value];
        // Calculate relative luminance
        $luminance = (0.299 * $rgb[0] + 0.587 * $rgb[1] + 0.114 * $rgb[2]) / 255;

        return $luminance < 0.5;
    }

    /**
     * Check if color is light
     */
    public function isLight(): bool
    {
        return ! $this->isDark();
    }

    /**
     * Check if color is warm (reds, oranges, yellows)
     */
    public function isWarm(): bool
    {
        if ($this === self::TRANSPARENT) {
            return false;
        }
        $rgb = self::RGB_MAP[$this->value];

        return $rgb[0] > $rgb[2] && $rgb[1] > $rgb[2];
    }

    /**
     * Check if color is cool (blues, purples, cyans)
     */
    public function isCool(): bool
    {
        if ($this === self::TRANSPARENT) {
            return false;
        }
        $rgb = self::RGB_MAP[$this->value];

        return $rgb[2] > $rgb[0] && $rgb[2] > $rgb[1];
    }

    /**
     * Get a contrasting text color (black or white)
     */
    public function getContrastColor(): self
    {
        return $this->isDark() ? self::WHITE : self::BLACK;
    }

    /**
     * Get complementary color
     */
    public function getComplementary(): ?self
    {
        if ($this === self::TRANSPARENT) {
            return null;
        }

        $rgb = self::RGB_MAP[$this->value];
        $complementary = [
            255 - $rgb[0],
            255 - $rgb[1],
            255 - $rgb[2],
        ];

        // Find the closest named color
        return $this->findClosestColor($complementary);
    }

    /**
     * Try to get color from hex value
     */
    public static function tryFromHex(string $hex): ?self
    {
        $hex = strtoupper($hex);
        foreach (self::HEX_MAP as $name => $colorHex) {
            if (strtoupper($colorHex) === $hex) {
                return self::tryFrom($name);
            }
        }

        return null;
    }

    /**
     * Try to get color from RGB components
     */
    public static function tryFromRgb(int $r, int $g, int $b): ?self
    {
        foreach (self::RGB_MAP as $name => $rgb) {
            if ($rgb && $rgb[0] === $r && $rgb[1] === $g && $rgb[2] === $b) {
                return self::tryFrom($name);
            }
        }

        return null;
    }

    /**
     * Get all colors grouped by hue family
     *
     * @return array<string, array<self>>
     */
    public static function getColorsByFamily(): array
    {
        return [
            'reds' => [self::RED, self::DARK_RED, self::FIREBRICK, self::INDIAN_RED, self::LIGHT_CORAL, self::SALMON],
            'pinks' => [self::PINK, self::LIGHT_PINK, self::HOT_PINK, self::DEEP_PINK],
            'oranges' => [self::ORANGE, self::DARK_ORANGE, self::CORAL, self::TOMATO],
            'yellows' => [self::YELLOW, self::LIGHT_YELLOW, self::GOLD],
            'browns' => [self::BROWN, self::MAROON, self::SIENNA, self::CHOCOLATE],
            'greens' => [self::GREEN, self::DARK_GREEN, self::FOREST_GREEN, self::LIME, self::LIGHT_GREEN],
            'cyans' => [self::CYAN, self::AQUA, self::LIGHT_CYAN, self::DARK_CYAN, self::TEAL],
            'blues' => [self::BLUE, self::DARK_BLUE, self::NAVY, self::ROYAL_BLUE, self::SKY_BLUE],
            'purples' => [self::PURPLE, self::INDIGO, self::MEDIUM_PURPLE, self::ORCHID, self::LAVENDER],
            'grays' => [self::GRAY, self::DARK_GRAY, self::LIGHT_GRAY, self::SILVER],
            'special' => [self::BLACK, self::WHITE, self::TRANSPARENT],
        ];
    }

    /**
     * Get all hex color codes
     *
     * @return array<string, string>
     */
    public static function getHexMap(): array
    {
        return self::HEX_MAP;
    }

    /**
     * Convert RGB to HSL
     *
     * @return array{0: float, 1: float, 2: float}
     */
    private function rgbToHsl(int $r, int $g, int $b): array
    {
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;

        if ($max === $min) {
            return [0, 0, $l * 100];
        }

        $d = $max - $min;
        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

        if ($max === $r) {
            $h = ($g - $b) / $d + ($g < $b ? 6 : 0);
        } elseif ($max === $g) {
            $h = ($b - $r) / $d + 2;
        } else {
            $h = ($r - $g) / $d + 4;
        }

        $h /= 6;

        return [$h * 360, $s * 100, $l * 100];
    }

    /**
     * Find closest named color for RGB values
     */
    private function findClosestColor(array $rgb): ?self
    {
        $minDistance = INF;
        $closest = null;

        foreach (self::RGB_MAP as $name => $target) {
            if ($target === null) {
                continue;
            }

            $distance = sqrt(
                pow($rgb[0] - $target[0], 2) +
                pow($rgb[1] - $target[1], 2) +
                pow($rgb[2] - $target[2], 2)
            );

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closest = $name;
            }
        }

        return $closest ? self::tryFrom($closest) : null;
    }
}
