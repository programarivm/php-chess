<?php

namespace Chess\Variant\Classical\PGN;

use Chess\Exception\UnknownNotationException;

/**
 * Tag.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class Tag extends AbstractNotation
{
    // STR (Seven Tag Roster)
    const EVENT = 'Event';
    const SITE = 'Site';
    const DATE = 'Date';
    const ROUND = 'Round';
    const WHITE = 'White';
    const BLACK = 'Black';
    const RESULT = 'Result';

    // FICS database
    const FICS_GAMES_DB_GAME_NO = 'FICSGamesDBGameNo';

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
    const WHITE_FIDE_ID = 'WhiteFideId';
    const BLACK_FIDE_ID = 'BlackFideId';
    const WHITE_TEAM = 'WhiteTeam';
    const BLACK_TEAM = 'BlackTeam';

    // event related information
    const EVENT_DATE = 'EventDate';
    const EVENT_SPONSOR = 'EventSponsor';
    const EVENT_TYPE = 'EventType';
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
    const TIME_CONTROL = 'TimeControl';
    const UTC_TIME = 'UTCTime';
    const UTC_DATE = 'UTCDate';

    // clock
    const WHITE_CLOCK = 'WhiteClock';
    const BLACK_CLOCK = 'BlackClock';

    // alternative starting positions
    const SET_UP = 'SetUp';
    const FEN = 'FEN';

    // game conclusion
    const TERMINATION = 'Termination';

    // miscellaneous
    const ANNOTATOR = 'Annotator';
    const MODE = 'Mode';
    const PLY_COUNT = 'PlyCount';
    const WHITE_RD = 'WhiteRD';
    const BLACK_RD = 'BlackRD';

    /**
     * Validation.
     *
     * @param string $tag
     * @return object if the tag is valid
     * @throws UnknownNotationException
     */
    public function validate(string $tag): object
    {
        $isValid = false;

        foreach ($this->values() as $val) {
            if (preg_match('/^\[' . $val . ' \"(.*)\"\]$/', $tag)) {
                $isValid = true;
            }
        }

        if (!$isValid) {
            throw new UnknownNotationException();
        }

        $exploded = explode(' "', $tag);

        $result = (object) [
            'name' => substr($exploded[0], 1),
            'value' => substr($exploded[1], 0, -2),
        ];

        return $result;
    }

    /**
     * Basic mandatory tags expected to be found in a game.
     *
     * @return array
     */
    public function mandatory(): array
    {
        return [
            self::EVENT,
            self::SITE,
            self::DATE,
            self::WHITE,
            self::BLACK,
            self::RESULT,
        ];
    }

    /**
     * Tags to be loaded into the database.
     *
     * @return array
     */
    public function loadable(): array
    {
        return [
            self::EVENT,
            self::SITE,
            self::DATE,
            self::FEN,
            self::WHITE,
            self::BLACK,
            self::RESULT,
            self::WHITE_ELO,
            self::BLACK_ELO,
            self::ECO,
        ];
    }
}
