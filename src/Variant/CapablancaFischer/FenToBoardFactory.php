<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArrayFactory;
use Chess\Variant\Capablanca\FEN\Str;
use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\Classical\FenToBoardFactory as ClassicalFenToBoardFactory;

class FenToBoardFactory
{
    public static function create(string $string, array $startPos): AbstractBoard
    {
        $fenStr = new Str();
        $string = $fenStr->validate($string);
        $fields = array_filter(explode(' ', $string));
        $namespace = 'Capablanca';
        try {
            $pieces = PieceArrayFactory::create(
                $fenStr->toArray($fields[0]),
                new Square(),
                new CastlingRule($startPos),
                $namespace
            );
            $board = new Board($startPos, $pieces, $fields[2]);
            $board->turn = $fields[1];
            $board->startFen = $string;
            ClassicalFenToBoardFactory::enPassant($fields, $board);
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $board;
    }
}
