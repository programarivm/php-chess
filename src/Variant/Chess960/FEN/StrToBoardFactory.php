<?php

namespace Chess\Variant\Chess960\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArrayFactory;
use Chess\Variant\Chess960\Board;
use Chess\Variant\Chess960\CastlingRule;
use Chess\Variant\Classical\FEN\Str;
use Chess\Variant\Classical\FEN\StrToBoardFactory as ClassicalFenStrToBoardFactory;
use Chess\Variant\Classical\PGN\Square;

class StrToBoardFactory
{
    public static function create(string $string, array $startPos): AbstractBoard
    {
        $fenStr = new Str();
        $string = $fenStr->validate($string);
        $fields = array_filter(explode(' ', $string));
        $namespace = 'Classical';
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
            ClassicalFenStrToBoardFactory::enPassant($fields, $board);
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $board;
    }
}
