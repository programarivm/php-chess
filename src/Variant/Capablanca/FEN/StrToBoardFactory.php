<?php

namespace Chess\Variant\Capablanca\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArrayFactory;
use Chess\Variant\Capablanca\Board;
use Chess\Variant\Capablanca\CastlingRule;
use Chess\Variant\Capablanca\FEN\Str;
use Chess\Variant\Capablanca\PGN\Piece;
use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\Classical\FEN\StrToBoardFactory as ClassicalFenStrToBoardFactory;

class StrToBoardFactory
{
    public static function create(string $string): AbstractBoard
    {
        $fenStr = new Str();
        $string = $fenStr->validate($string);
        $fields = array_filter(explode(' ', $string));
        $namespace = 'Capablanca';

        try {
            $pieces = PieceArrayFactory::create(
                $fenStr->toArray($fields[0]),
                new Square(),
                new CastlingRule(),
                $namespace
            );
            $board = new Board($pieces, $fields[2]);
            $board->turn = $fields[1];
            $board->startFen = $string;
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        ClassicalFenStrToBoardFactory::enPassant($fields, $board);

        return $board;
    }
}
