<?php

namespace Chess\Variant\Losing\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArray;
use Chess\Variant\Classical\CastlingRule;
use Chess\Variant\Classical\FEN\StrToBoard as ClassicalFenStrToBoard;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Losing\Board;
use Chess\Variant\Losing\FEN\Str;

class StrToBoard extends ClassicalFenStrToBoard
{
    public function __construct(string $string)
    {
        $this->square = new Square();
        $this->fenStr = new Str();
        $this->string = $this->fenStr->validate($string);
        $this->fields = array_filter(explode(' ', $this->string));
        $this->castlingAbility = '-';
        $this->castlingRule = new CastlingRule();
        $this->namespace = 'Losing';
    }

    public function create(): AbstractBoard
    {
        try {
            $pieces = (new PieceArray(
                $this->fenStr->toArray($this->fields[0]),
                $this->square,
                $this->castlingRule,
                $this->namespace
            ))->pieces;
            $board = new Board($pieces, $this->castlingAbility);
            $board->turn = $this->fields[1];
            $board->startFen = $this->string;
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $this->enPassant($board);
    }
}
