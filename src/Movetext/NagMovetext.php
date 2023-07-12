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
            'nag' => '$0',
            'meaning' => 'Null annotation',
        ],
        [
            'nag' => '$1',
            'meaning' => 'good move',
            'symbol' => '!',
        ],
        [
            'nag' => '$2',
            'meaning' => 'mistake',
            'symbol' => '?',
        ],
        [
            'nag' => '$3',
            'meaning' => 'very good move',
            'symbol' => '!!',
        ],
        [
            'nag' => '$4',
            'meaning' => 'blunder',
            'symbol' => '??',
        ],
        [
            'nag' => '$5',
            'meaning' => 'interesting move',
            'symbol' => '!?',
        ],
        [
            'nag' => '$6',
            'meaning' => 'questionable move',
            'symbol' => '?!',
        ],
        [
            'nag' => '$7',
            'meaning' => 'forced move',
            'symbol' => '□',
        ],
        [
            'nag' => '$8',
            'meaning' => 'singular move',
        ],
        [
            'nag' => '$9',
            'meaning' => 'worse move',
        ],
        [
            'nag' => '$10',
            'meaning' => 'drawish position',
            'symbol' => '=',
        ],
        [
            'nag' => '$11',
            'meaning' => 'equal chances, quiet position',
        ],
        [
            'nag' => '$12',
            'meaning' => 'equal chances, active position',
        ],
        [
            'nag' => '$13',
            'meaning' => 'unclear position',
            'symbol' => '∞',
        ],
        [
            'nag' => '$14',
            'meaning' => 'White has a slight advantage',
            'symbol' => '⩲',
        ],
        [
            'nag' => '$15',
            'meaning' => 'Black has a slight advantage',
            'symbol' => '⩱',
        ],
        [
            'nag' => '$16',
            'meaning' => 'White has a moderate advantage',
            'symbol' => '±',
        ],
        [
            'nag' => '$17',
            'meaning' => 'Black has a moderate advantage',
            'symbol' => '∓',
        ],
        [
            'nag' => '$18',
            'meaning' => 'White has a decisive advantage',
            'symbol' => '+ −',
        ],
        [
            'nag' => '$19',
            'meaning' => 'Black has a decisive advantage',
            'symbol' => '− +',
        ],
        [
            'nag' => '$20',
            'meaning' => 'White has a crushing advantage (Black should resign)',
        ],
        [
            'nag' => '$21',
            'meaning' => 'Black has a crushing advantage (White should resign)',
        ],
        [
            'nag' => '$22',
            'meaning' => 'White is in zugzwang',
            'symbol' => '⨀',
        ],
        [
            'nag' => '$23',
            'meaning' => 'Black is in zugzwang',
            'symbol' => '⨀',
        ],
        [
            'nag' => '$24',
            'meaning' => 'White has a slight space advantage',
        ],
        [
            'nag' => '$25',
            'meaning' => 'Black has a slight space advantage',
        ],
        [
            'nag' => '$26',
            'meaning' => 'White has a moderate space advantage',
            'symbol' => '○',
        ],
        [
            'nag' => '$27',
            'meaning' => 'Black has a moderate space advantage',
            'symbol' => '○',
        ],
        [
            'nag' => '$28',
            'meaning' => 'White has a decisive space advantage',
        ],
        [
            'nag' => '$29',
            'meaning' => 'Black has a decisive space advantage',
        ],
        [
            'nag' => '$30',
            'meaning' => 'White has a slight time (development) advantage',
        ],
        [
            'nag' => '$31',
            'meaning' => 'Black has a slight time (development) advantage',
        ],
        [
            'nag' => '$32',
            'meaning' => 'White has a moderate time (development) advantage',
            'symbol' => '⟳',
        ],
        [
            'nag' => '$33',
            'meaning' => 'Black has a moderate time (development) advantage',
            'symbol' => '⟳',
        ],
        [
            'nag' => '$34',
            'meaning' => 'White has a decisive time (development) advantage',
        ],
        [
            'nag' => '$35',
            'meaning' => 'Black has a decisive time (development) advantage',
        ],
        [
            'nag' => '$36',
            'meaning' => 'White has the initiative',
            'symbol' => '↑',
        ],
        [
            'nag' => '$37',
            'meaning' => 'Black has the initiative',
            'symbol' => '↑',
        ],
        [
            'nag' => '$38',
            'meaning' => 'White has a lasting initiative',
        ],
        [
            'nag' => '$39',
            'meaning' => 'Black has a lasting initiative',
        ],
        [
            'nag' => '$40',
            'meaning' => 'White has the attack',
            'symbol' => '→',
        ],
        [
            'nag' => '$41',
            'meaning' => 'Black has the attack',
            'symbol' => '→',
        ],
        [
            'nag' => '$42',
            'meaning' => 'White has insufficient compensation for material deficit',
        ],
        [
            'nag' => '$43',
            'meaning' => 'Black has insufficient compensation for material deficit',
        ],
        [
            'nag' => '$44',
            'meaning' => 'White has sufficient compensation for material deficit',
            'symbol' => '⯹',
        ],
        [
            'nag' => '$45',
            'meaning' => 'Black has sufficient compensation for material deficit',
            'symbol' => '⯹',
        ],
        [
            'nag' => '$46',
            'meaning' => 'White has more than adequate compensation for material deficit',
        ],
        [
            'nag' => '$47',
            'meaning' => 'Black has more than adequate compensation for material deficit',
        ],
        [
            'nag' => '$48',
            'meaning' => 'White has a slight center control advantage',
        ],
        [
            'nag' => '$49',
            'meaning' => 'Black has a slight center control advantage',
        ],
        [
            'nag' => '$50',
            'meaning' => 'White has a moderate center control advantage',
        ],
        [
            'nag' => '$51',
            'meaning' => 'Black has a moderate center control advantage',
        ],
        [
            'nag' => '$52',
            'meaning' => 'White has a decisive center control advantage',
        ],
        [
            'nag' => '$53',
            'meaning' => 'Black has a decisive center control advantage',
        ],
        [
            'nag' => '$54',
            'meaning' => 'White has a slight kingside control advantage',
        ],
        [
            'nag' => '$55',
            'meaning' => 'Black has a slight kingside control advantage',
        ],
        [
            'nag' => '$56',
            'meaning' => 'White has a moderate kingside control advantage',
        ],
        [
            'nag' => '$57',
            'meaning' => 'Black has a moderate kingside control advantage',
        ],
        [
            'nag' => '$58',
            'meaning' => 'White has a decisive kingside control advantage',
        ],
        [
            'nag' => '$59',
            'meaning' => 'Black has a decisive kingside control advantage',
        ],
        [
            'nag' => '$60',
            'meaning' => 'White has a slight queenside control advantage',
        ],
        [
            'nag' => '$61',
            'meaning' => 'Black has a slight queenside control advantage',
        ],
        [
            'nag' => '$62',
            'meaning' => 'White has a moderate queenside control advantage',
        ],
        [
            'nag' => '$63',
            'meaning' => 'Black has a moderate queenside control advantage',
        ],
        [
            'nag' => '$64',
            'meaning' => 'White has a decisive queenside control advantage',
        ],
        [
            'nag' => '$65',
            'meaning' => 'Black has a decisive queenside control advantage',
        ],
        [
            'nag' => '$66',
            'meaning' => 'White has a vulnerable first rank',
        ],
        [
            'nag' => '$67',
            'meaning' => 'Black has a vulnerable first rank',
        ],
        [
            'nag' => '$68',
            'meaning' => 'White has a well protected first rank',
        ],
        [
            'nag' => '$69',
            'meaning' => 'Black has a well protected first rank',
        ],
        [
            'nag' => '$70',
            'meaning' => 'White has a poorly protected king',
        ],
        [
            'nag' => '$71',
            'meaning' => 'Black has a poorly protected king',
        ],
        [
            'nag' => '$72',
            'meaning' => 'White has a well protected king',
        ],
        [
            'nag' => '$73',
            'meaning' => 'Black has a well protected king',
        ],
        [
            'nag' => '$74',
            'meaning' => 'White has a poorly placed king',
        ],
        [
            'nag' => '$75',
            'meaning' => 'Black has a poorly placed king',
        ],
        [
            'nag' => '$76',
            'meaning' => 'White has a well placed king',
        ],
        [
            'nag' => '$77',
            'meaning' => 'Black has a well placed king',
        ],
        [
            'nag' => '$78',
            'meaning' => 'White has a very weak pawn structure',
        ],
        [
            'nag' => '$79',
            'meaning' => 'Black has a very weak pawn structure',
        ],
        [
            'nag' => '$80',
            'meaning' => 'White has a moderately weak pawn structure',
        ],
        [
            'nag' => '$81',
            'meaning' => 'Black has a moderately weak pawn structure',
        ],
        [
            'nag' => '$82',
            'meaning' => 'White has a moderately strong pawn structure',
        ],
        [
            'nag' => '$83',
            'meaning' => 'Black has a moderately strong pawn structure',
        ],
        [
            'nag' => '$84',
            'meaning' => 'White has a very strong pawn structure',
        ],
        [
            'nag' => '$85',
            'meaning' => 'Black has a very strong pawn structure',
        ],
        [
            'nag' => '$86',
            'meaning' => 'White has poor knight placement',
        ],
        [
            'nag' => '$87',
            'meaning' => 'Black has poor knight placement',
        ],
        [
            'nag' => '$88',
            'meaning' => 'White has good knight placement',
        ],
        [
            'nag' => '$89',
            'meaning' => 'Black has good knight placement',
        ],
        [
            'nag' => '$90',
            'meaning' => 'White has poor bishop placement',
        ],
        [
            'nag' => '$91',
            'meaning' => 'Black has poor bishop placement',
        ],
        [
            'nag' => '$92',
            'meaning' => 'White has good bishop placement',
        ],
        [
            'nag' => '$93',
            'meaning' => 'Black has good bishop placement',
        ],
        [
            'nag' => '$94',
            'meaning' => 'White has poor rook placement',
        ],
        [
            'nag' => '$95',
            'meaning' => 'Black has poor rook placement',
        ],
        [
            'nag' => '$96',
            'meaning' => 'White has good rook placement',
        ],
        [
            'nag' => '$97',
            'meaning' => 'Black has good rook placement',
        ],
        [
            'nag' => '$98',
            'meaning' => 'White has poor queen placement',
        ],
        [
            'nag' => '$99',
            'meaning' => 'Black has poor queen placement',
        ],
        [
            'nag' => '$100',
            'meaning' => 'White has good queen placement',
        ],
        [
            'nag' => '$101',
            'meaning' => 'Black has good queen placement',
        ],
        [
            'nag' => '$102',
            'meaning' => 'White has poor piece coordination',
        ],
        [
            'nag' => '$103',
            'meaning' => 'Black has poor piece coordination',
        ],
        [
            'nag' => '$104',
            'meaning' => 'White has good piece coordination',
        ],
        [
            'nag' => '$105',
            'meaning' => 'Black has good piece coordination',
        ],
        [
            'nag' => '$106',
            'meaning' => 'White has played the opening very poorly',
        ],
        [
            'nag' => '$107',
            'meaning' => 'Black has played the opening very poorly',
        ],
        [
            'nag' => '$108',
            'meaning' => 'White has played the opening poorly',
        ],
        [
            'nag' => '$109',
            'meaning' => 'Black has played the opening poorly',
        ],
        [
            'nag' => '$110',
            'meaning' => 'White has played the opening well',
        ],
        [
            'nag' => '$111',
            'meaning' => 'Black has played the opening well',
        ],
        [
            'nag' => '$112',
            'meaning' => 'White has played the opening very well',
        ],
        [
            'nag' => '$113',
            'meaning' => 'Black has played the opening very well',
        ],
        [
            'nag' => '$114',
            'meaning' => 'White has played the middlegame very poorly',
        ],
        [
            'nag' => '$115',
            'meaning' => 'Black has played the middlegame very poorly',
        ],
        [
            'nag' => '$116',
            'meaning' => 'White has played the middlegame poorly',
        ],
        [
            'nag' => '$117',
            'meaning' => 'Black has played the middlegame poorly',
        ],
        [
            'nag' => '$118',
            'meaning' => 'White has played the middlegame well',
        ],
        [
            'nag' => '$119',
            'meaning' => 'Black has played the middlegame well',
        ],
        [
            'nag' => '$120',
            'meaning' => 'White has played the middlegame very well',
        ],
        [
            'nag' => '$121',
            'meaning' => 'Black has played the middlegame very well',
        ],
        [
            'nag' => '$122',
            'meaning' => 'White has played the ending very poorly',
        ],
        [
            'nag' => '$123',
            'meaning' => 'Black has played the ending very poorly',
        ],
        [
            'nag' => '$124',
            'meaning' => 'White has played the ending poorly',
        ],
        [
            'nag' => '$125',
            'meaning' => 'Black has played the ending poorly',
        ],
        [
            'nag' => '$126',
            'meaning' => 'White has played the ending well',
        ],
        [
            'nag' => '$127',
            'meaning' => 'Black has played the ending well',
        ],
        [
            'nag' => '$128',
            'meaning' => 'White has played the ending very well',
        ],
        [
            'nag' => '$129',
            'meaning' => 'Black has played the ending very well',
        ],
        [
            'nag' => '$130',
            'meaning' => 'White has slight counterplay',
        ],
        [
            'nag' => '$131',
            'meaning' => 'Black has slight counterplay',
        ],
        [
            'nag' => '$132',
            'meaning' => 'White has moderate counterplay',
            'symbol' => '⇆',
        ],
        [
            'nag' => '$133',
            'meaning' => 'Black has moderate counterplay',
            'symbol' => '⇆',
        ],
        [
            'nag' => '$134',
            'meaning' => 'White has decisive counterplay',
        ],
        [
            'nag' => '$135',
            'meaning' => 'Black has decisive counterplay',
        ],
        [
            'nag' => '$136',
            'meaning' => 'White has moderate time control pressure',
        ],
        [
            'nag' => '$137',
            'meaning' => 'Black has moderate time control pressure',
        ],
        [
            'nag' => '$138',
            'meaning' => 'White has severe time control pressure / zeitnot',
            'symbol' => '⨁',
        ],
        [
            'nag' => '$139',
            'meaning' => 'Black has severe time control pressure / zeitnot',
            'symbol' => '⨁',
        ],
    ];

    /**
     * Returns a glyph.
     *
     * @param string $nag
     * @return array
     */
    public static function glyph(string $nag): ?array
    {
        foreach (self::$glyphs as $glyph) {
            if ($glyph['nag'] === $nag) {
                return $glyph;
            }
        }

        return null;
    }
}
