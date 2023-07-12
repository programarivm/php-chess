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
        ],
        [
            'n' => 9,
            'meaning' => 'worse move',
        ],
        [
            'n' => 10,
            'meaning' => 'drawish position',
            'symbol' => '=',
        ],
        [
            'n' => 11,
            'meaning' => 'equal chances, quiet position',
        ],
        [
            'n' => 12,
            'meaning' => 'equal chances, active position',
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
        ],
        [
            'n' => 21,
            'meaning' => 'Black has a crushing advantage (White should resign)',
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
        ],
        [
            'n' => 25,
            'meaning' => 'Black has a slight space advantage',
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
        ],
        [
            'n' => 29,
            'meaning' => 'Black has a decisive space advantage',
        ],
        [
            'n' => 30,
            'meaning' => 'White has a slight time (development) advantage',
        ],
        [
            'n' => 31,
            'meaning' => 'Black has a slight time (development) advantage',
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
        ],
        [
            'n' => 35,
            'meaning' => 'Black has a decisive time (development) advantage',
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
        ],
        [
            'n' => 39,
            'meaning' => 'Black has a lasting initiative',
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
        [
            'n' => 42,
            'meaning' => 'White has insufficient compensation for material deficit',
        ],
        [
            'n' => 43,
            'meaning' => 'Black has insufficient compensation for material deficit',
        ],
        [
            'n' => 44,
            'meaning' => 'White has sufficient compensation for material deficit',
            'symbol' => '⯹',
        ],
        [
            'n' => 45,
            'meaning' => 'Black has sufficient compensation for material deficit',
            'symbol' => '⯹',
        ],
        [
            'n' => 46,
            'meaning' => 'White has more than adequate compensation for material deficit',
        ],
        [
            'n' => 47,
            'meaning' => 'Black has more than adequate compensation for material deficit',
        ],
        [
            'n' => 48,
            'meaning' => 'White has a slight center control advantage',
        ],
        [
            'n' => 49,
            'meaning' => 'Black has a slight center control advantage',
        ],
        [
            'n' => 50,
            'meaning' => 'White has a moderate center control advantage',
        ],
        [
            'n' => 51,
            'meaning' => 'Black has a moderate center control advantage',
        ],
        [
            'n' => 52,
            'meaning' => 'White has a decisive center control advantage',
        ],
        [
            'n' => 53,
            'meaning' => 'Black has a decisive center control advantage',
        ],
        [
            'n' => 54,
            'meaning' => 'White has a slight kingside control advantage',
        ],
        [
            'n' => 55,
            'meaning' => 'Black has a slight kingside control advantage',
        ],
        [
            'n' => 56,
            'meaning' => 'White has a moderate kingside control advantage',
        ],
        [
            'n' => 57,
            'meaning' => 'Black has a moderate kingside control advantage',
        ],
        [
            'n' => 58,
            'meaning' => 'White has a decisive kingside control advantage',
        ],
        [
            'n' => 59,
            'meaning' => 'Black has a decisive kingside control advantage',
        ],
        [
            'n' => 60,
            'meaning' => 'White has a slight queenside control advantage',
        ],
        [
            'n' => 61,
            'meaning' => 'Black has a slight queenside control advantage',
        ],
        [
            'n' => 62,
            'meaning' => 'White has a moderate queenside control advantage',
        ],
        [
            'n' => 63,
            'meaning' => 'Black has a moderate queenside control advantage',
        ],
        [
            'n' => 64,
            'meaning' => 'White has a decisive queenside control advantage',
        ],
        [
            'n' => 65,
            'meaning' => 'Black has a decisive queenside control advantage',
        ],
        [
            'n' => 66,
            'meaning' => 'White has a vulnerable first rank',
        ],
        [
            'n' => 67,
            'meaning' => 'Black has a vulnerable first rank',
        ],
        [
            'n' => 68,
            'meaning' => 'White has a well protected first rank',
        ],
        [
            'n' => 69,
            'meaning' => 'Black has a well protected first rank',
        ],
        [
            'n' => 70,
            'meaning' => 'White has a poorly protected king',
        ],
        [
            'n' => 71,
            'meaning' => 'Black has a poorly protected king',
        ],
        // TODO:
        // Add more glyphs from https://en.wikipedia.org/wiki/Numeric_Annotation_Glyphs
        [
            'n' => 132,
            'meaning' => 'White has moderate counterplay',
            'symbol' => '⇆',
        ],
        [
            'n' => 133,
            'meaning' => 'Black has moderate counterplay',
            'symbol' => '⇆',
        ],
        [
            'n' => 134,
            'meaning' => 'White has decisive counterplay',
        ],
        [
            'n' => 135,
            'meaning' => 'Black has decisive counterplay',
        ],
        [
            'n' => 136,
            'meaning' => 'White has moderate time control pressure',
        ],
        [
            'n' => 137,
            'meaning' => 'Black has moderate time control pressure',
        ],
        [
            'n' => 138,
            'meaning' => 'White has severe time control pressure / zeitnot',
            'symbol' => '⨁',
        ],
        [
            'n' => 139,
            'meaning' => 'Black has severe time control pressure / zeitnot',
            'symbol' => '⨁',
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
