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

    public static function init(string $text): Movetext
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

    public static function toArray(): \stdClass
    {
        return self::$movetext;
    }

    /**
     * Filters a movetext.
     *
     *      Example:
     *
     *          1.e4  e5 2.  f4 exf4 3. Bc4 d5 4.Bxd5 Qh4+
     *
     *      is transformed into:
     *
     *          1.e4 e5 2.f4 exf4 3.Bc4 d5 4.Bxd5 Qh4+
     *
     * @return string
     */
    public static function filter(): string
    {
        $text = '';
        for ($i = 0; $i < count(self::$movetext->numbers); $i++) {
            $text .= self::$movetext->numbers[$i] . '.' .
                self::$movetext->notations[$i*2] . ' ' .
                self::$movetext->notations[$i*2+1] . ' ';
        }

        return trim($text);
    }
}
