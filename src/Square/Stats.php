<?php
namespace PGNChess\Square;

use PGNChess\PGN\Symbol;

/**
 * Computes statistical operations regarding the squares of the board.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Stats
{
    /**
     * Calculates the free/used squares.
     *
     * @param array $pieces
     * @return \stdClass
     */
    public static function calc(array $pieces): \stdClass
    {
        return (object) [
            'used' => self::getUsed($pieces),
            'free' => self::getFree($pieces)
        ];
    }

    /**
     * Returns all the board's squares.
     *
     * @return array
     */
    private static function getAll(): array
    {
        $squares = [];

        for($i=0; $i<8; $i++) {
            for($j=1; $j<=8; $j++) {
                $squares[] = chr((ord('a') + $i)) . $j;
            }
        }

        return $squares;
    }

    /**
     * Returns the squares currently being used by both players.
     *
     * @return array
     */
    private static function getUsed(array $pieces): \stdClass
    {
        $squares = (object) [
            Symbol::WHITE => [],
            Symbol::BLACK => []
        ];

        foreach ($pieces as $piece) {
            $squares->{$piece->getColor()}[] = $piece->getPosition();
        }

        return $squares;
    }

    /**
     * Returns the free squares.
     *
     * @return array
     */
    private static function getFree(array $pieces): array
    {
        $usedSquares = self::getUsed($pieces);

        return array_values(
            array_diff(
                self::getAll(),
                array_merge($usedSquares->{Symbol::WHITE}, $usedSquares->{Symbol::BLACK})
        ));
    }
}
