<?php

namespace Chess\Variant\Classical;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArrayFactory;
use Chess\Variant\Classical\FEN\Str;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

/**
 * FEN to Board Factory
 *
 * A factory of classical chess boards.
 */
class FenToBoardFactory
{
    /**
     * @param string|null $string
     * @return \Chess\Variant\AbstractBoard
     */
    public static function create(string $string = null): AbstractBoard
    {
        if (!$string) {
            return new Board();
        }
              
        try {
            $fenStr = new Str();
            $string = $fenStr->validate($string);
            $fields = array_filter(explode(' ', $string));
            $namespace = 'Classical';
            $pieces = PieceArrayFactory::create(
                $fenStr->toArray($fields[0]),
                new Square(),
                new CastlingRule(),
                $namespace
            );
            $board = new Board($pieces, $fields[2]);
            $board->turn = $fields[1];
            $board->startFen = $string;
            self::enPassant($fields, $board);
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

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
