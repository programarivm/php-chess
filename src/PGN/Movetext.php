<?php

namespace PGNChess\PGN;

/**
 * Movetext class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
final class Movetext
{
    protected static $movetext;

    public static function init($text)
    {
        self::$movetext = (object) [
            'numbers' => [],
            'notations' => [],
        ];

        $moves = array_filter(explode(' ', $text));

        foreach ($moves as $move) {
            if (preg_match('/^[1-9][0-9]*\.(.*)$/', $move)) {
                $moveExploded = explode('.', $move);
                self::$movetext->numbers[] = $moveExploded[0];
                self::$movetext->notations[] = $moveExploded[1];
            } else {
                self::$movetext->notations[] = $move;
            }
        }

        self::$movetext->notations = array_values(array_filter(self::$movetext->notations));

        return new static;
    }

    public static function toArray()
    {
        return self::$movetext;
    }
}
