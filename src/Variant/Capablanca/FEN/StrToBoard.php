<?php

namespace Chess\Variant\Capablanca\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Piece\PieceArray;
use Chess\Variant\Capablanca\Board;
use Chess\Variant\Capablanca\FEN\Str;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Capablanca\Rule\CastlingRule;
use Chess\Variant\Classical\FEN\StrToBoard as ClassicalFenStrToBoard;

/**
 * StrToBoard
 *
 * Converts a FEN string to a chessboard object.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class StrToBoard extends ClassicalFenStrToBoard
{
    public function __construct(string $string)
    {
        $this->size = Square::SIZE;
        $this->fenStr = new Str();
        $this->string = $this->fenStr->validate($string);
        $this->fields = array_filter(explode(' ', $this->string));
        $this->castlingAbility = $this->fields[2];
        $this->castlingRule = (new CastlingRule())->getRule();
    }

    public function create(): Board
    {
        try {
            $pieces = (new PieceArray(
                $this->fenStr->toAsciiArray($this->fields[0]),
                $this->size,
                $this->castlingRule
            ))->getArray();
            $board = (new Board(
                $pieces,
                $this->castlingAbility
            ))->setTurn($this->fields[1])->setStartFen($this->string);
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $this->enPassant($board);
    }
}
