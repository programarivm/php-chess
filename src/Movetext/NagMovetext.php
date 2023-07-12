<?php

namespace Chess\Movetext;

/**
 * Numeric Annotation Glyphs.
 *
 * @license GPL
 */
class NagMovetext
{
    /**
     * Array of NAGs.
     *
     * @var array
     */
    public static $glyphs = [
        [
            'n' => 0,
            'meaning' => 'Null annotation',
            'symbol' => null,
        ],
        [
            'n' => 1,
            'meaning' => 'good move',
            'symbol' => '!',
        ],
        [
            'n' => 2,
            'meaning' => 'mistake',
            'symbol' => '?',
        ],
        [
            'n' => 3,
            'meaning' => 'very good move',
            'symbol' => '!!',
        ],
        [
            'n' => 4,
            'meaning' => 'blunder',
            'symbol' => '??',
        ],
        [
            'n' => 5,
            'meaning' => 'interesting move',
            'symbol' => '!?',
        ],
        [
            'n' => 6,
            'meaning' => 'questionable move',
            'symbol' => '?!',
        ],
        [
            'n' => 7,
            'meaning' => 'forced move',
            'symbol' => '□',
        ],
        [
            'n' => 8,
            'meaning' => 'singular move',
            'symbol' => null,
        ],
        [
            'n' => 9,
            'meaning' => 'worse move',
            'symbol' => null,
        ],
        [
            'n' => 10,
            'meaning' => 'drawish position',
            'symbol' => '=',
        ],
        [
            'n' => 11,
            'meaning' => 'equal chances, quiet position',
            'symbol' => null,
        ],
        [
            'n' => 12,
            'meaning' => 'equal chances, active position',
            'symbol' => null,
        ],
        [
            'n' => 13,
            'meaning' => 'unclear position',
            'symbol' => '∞',
        ],
        [
            'n' => 14,
            'meaning' => 'White has a slight advantage',
            'symbol' => '⩲',
        ],
        [
            'n' => 15,
            'meaning' => 'Black has a slight advantage',
            'symbol' => '⩱',
        ],
        [
            'n' => 16,
            'meaning' => 'White has a moderate advantage',
            'symbol' => '±',
        ],
        [
            'n' => 17,
            'meaning' => 'Black has a moderate advantage',
            'symbol' => '∓',
        ],
        [
            'n' => 18,
            'meaning' => 'White has a decisive advantage',
            'symbol' => '+ −',
        ],
        [
            'n' => 19,
            'meaning' => 'Black has a decisive advantage',
            'symbol' => '− +',
        ],
        [
            'n' => 20,
            'meaning' => 'White has a crushing advantage (Black should resign)',
            'symbol' => null,
        ],
        [
            'n' => 21,
            'meaning' => 'Black has a crushing advantage (White should resign)',
            'symbol' => null,
        ],
        [
            'n' => 22,
            'meaning' => 'White is in zugzwang',
            'symbol' => '⨀',
        ],
        [
            'n' => 23,
            'meaning' => 'Black is in zugzwang',
            'symbol' => '⨀',
        ],
        [
            'n' => 24,
            'meaning' => 'White has a slight space advantage',
            'symbol' => null,
        ],
        [
            'n' => 25,
            'meaning' => 'Black has a slight space advantage',
            'symbol' => null,
        ],
        [
            'n' => 26,
            'meaning' => 'White has a moderate space advantage',
            'symbol' => '○',
        ],
        [
            'n' => 27,
            'meaning' => 'Black has a moderate space advantage',
            'symbol' => '○',
        ],
        [
            'n' => 28,
            'meaning' => 'White has a decisive space advantage',
            'symbol' => null,
        ],
        [
            'n' => 29,
            'meaning' => 'Black has a decisive space advantage',
            'symbol' => null,
        ],
        [
            'n' => 30,
            'meaning' => 'White has a slight time (development) advantage',
            'symbol' => null,
        ],
        [
            'n' => 31,
            'meaning' => 'Black has a slight time (development) advantage',
            'symbol' => null,
        ],
        [
            'n' => 32,
            'meaning' => 'White has a moderate time (development) advantage',
            'symbol' => '⟳',
        ],
        [
            'n' => 33,
            'meaning' => 'Black has a moderate time (development) advantage',
            'symbol' => '⟳',
        ],
        [
            'n' => 34,
            'meaning' => 'White has a decisive time (development) advantage',
            'symbol' => null,
        ],
        [
            'n' => 35,
            'meaning' => 'Black has a decisive time (development) advantage',
            'symbol' => null,
        ],
        [
            'n' => 36,
            'meaning' => 'White has the initiative',
            'symbol' => '↑',
        ],
        [
            'n' => 37,
            'meaning' => 'Black has the initiative',
            'symbol' => '↑',
        ],
        [
            'n' => 38,
            'meaning' => 'White has a lasting initiative',
            'symbol' => null,
        ],
        [
            'n' => 39,
            'meaning' => 'Black has a lasting initiative',
            'symbol' => null,
        ],
        [
            'n' => 40,
            'meaning' => 'White has the attack',
            'symbol' => '→',
        ],
        [
            'n' => 41,
            'meaning' => 'Black has the attack',
            'symbol' => '→',
        ],
    ];

    /**
     * Returns a glyph if found in the array of glyphs.
     *
     * @param string $glyph
     * @return array
     */
    public static function glyph(string $glyph): ?array
    {
        if ($glyph) {
            $n = intval(ltrim($glyph, $glyph[0]));
            foreach (self::$glyphs as $key => $val) {
                if ($glyph[0] === '$' && $n === $key) {
                    return $val;
                }
            }
        }

        return null;
    }
}
