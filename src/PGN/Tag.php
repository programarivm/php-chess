<?php

namespace PGNChess\PGN;

/**
 * PGN tags.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Tag
{
	// STR (Seven Tag Roster)
    const EVENT = 'Event';
	const SITE = 'Site';
	const DATE = 'Date';
	const ROUND = 'Round';
	const WHITE = 'White';
	const BLACK = 'Black';
	const RESULT = 'Result';

	// player related information
	const WHITE_TITLE = 'WhiteTitle';
	const BLACK_TITLE = 'BlackTitle';
	const WHITE_ELO = 'WhiteElo';
	const BLACK_ELO = 'BlackElo';
	const WHITE_USCF = 'WhiteUSCF';
	const BLACK_USCF = 'BlackUSCF';
	const WHITE_NA = 'WhiteNA';
	const BLACK_NA = 'BlackNA';
	const WHITE_TYPE = 'WhiteType';
	const BLACK_TYPE = 'BlackType';

	// event related information
	const EVENT_DATE = 'EventDate';
	const EVENT_SPONSOR = 'EventSponsor';
	const SECTION = 'Section';
	const STAGE = 'Stage';
	const BOARD = 'Board';

	// opening information
	const OPENING = 'Opening';
	const VARIATION = 'Variation';
	const SUB_VARIATION = 'SubVariation';
	const ECO = 'ECO';
	const NIC = 'NIC';

	// time and date related information
	const TIME = 'Time';
	const UTC_TIME = 'UTCTime';
	const UTC_DATE = 'UTCDate';

	// time control
	const TIME_CONTROL = 'TimeControl';

	// alternative starting positions
	const SET_UP = 'SetUp';
	const FEN = 'FEN';

	// game conclusion
	const TERMINATION = 'Termination';

	// miscellaneous
	const ANNOTATOR = 'Annotator';
	const MODE = 'Mode';
	const PLY_COUNT = 'PlyCount';

    public static function getConstants(): array
    {
        return (new \ReflectionClass(get_called_class()))->getConstants();
    }

    public static function isStr(array $tags): bool
    {
        return isset($tags[Tag::EVENT]) &&
            isset($tags[Tag::SITE]) &&
            isset($tags[Tag::DATE]) &&
            isset($tags[Tag::ROUND]) &&
            isset($tags[Tag::WHITE]) &&
            isset($tags[Tag::BLACK]) &&
            isset($tags[Tag::RESULT]);
    }

    public static function reset(array &$tags)
    {
        $tags = [];
        foreach (Tag::getConstants() as $key => $value) {
            $tags[$value] = null;
        }
    }
}
