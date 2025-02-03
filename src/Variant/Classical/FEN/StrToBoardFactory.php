<?php

namespace Chess\Variant\Classical\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArrayFactory;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\CastlingRule;
use Chess\Variant\Classical\FEN\Str;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class StrToBoardFactory
{
    public static function create(string $string): AbstractBoard
    {
        $fenStr = new Str();
        $string = $fenStr->validate($string);
        $fields = array_filter(explode(' ', $string));
        $namespace = 'Classical';

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

        self::enPassant($fields, $board);

        return $board;
    }

    public static function enPassant(array $fields, AbstractBoard $board) 
    {
        if ($fields[3] !== '-') {
            foreach ($board->pieces($fields[1]) as $piece) {
                if ($piece->id === Piece::P) {
                    if (in_array($fields[3], $piece->xSqs)) {
                        $piece->xEnPassantSq = $fields[3];
                    }
                }
            }
        }
    }
}
