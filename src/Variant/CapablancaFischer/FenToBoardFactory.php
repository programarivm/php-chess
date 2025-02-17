<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArrayFactory;
use Chess\Variant\Capablanca\FEN\Str;
use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\Classical\FenToBoardFactory as ClassicalFenToBoardFactory;

/**
 * FEN to Board Factory
 *
 * A factory of Capablanca-Fischer chess boards.
 */
class FenToBoardFactory
{
    /**
     * @param string $string
     * @param array $shuffle
     * @return \Chess\Variant\AbstractBoard
     */
    public static function create(string $string, array $shuffle): AbstractBoard
    {
        $fenStr = new Str();
        $string = $fenStr->validate($string);
        $fields = array_filter(explode(' ', $string));
        $namespace = 'Capablanca';
        try {
            $pieces = PieceArrayFactory::create(
                $fenStr->toArray($fields[0]),
                new Square(),
                new CastlingRule($shuffle),
                $namespace
            );
            $board = new Board($shuffle, $pieces, $fields[2]);
            $board->turn = $fields[1];
            $board->startFen = $string;
            ClassicalFenToBoardFactory::enPassant($fields, $board);
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $board;
    }
}
