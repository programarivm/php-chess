<?php

namespace Chess\Variant\Dunsany\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArrayFactory;
use Chess\Variant\Classical\CastlingRule;
use Chess\Variant\Classical\FEN\StrToBoardFactory as ClassicalFenStrToBoardFactory;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Dunsany\Board;
use Chess\Variant\Dunsany\FEN\Str;

class StrToBoardFactory
{
    public static function create(string $string): AbstractBoard
    {
        $fenStr = new Str();
        $string = $fenStr->validate($string);
        $fields = array_filter(explode(' ', $string));
        $namespace = 'Dunsany';

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
