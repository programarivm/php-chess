<?php

namespace Chess\Variant\Dunsany\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArrayFactory;
use Chess\Variant\Classical\CastlingRule;
use Chess\Variant\Classical\FEN\StrToBoard as ClassicalFenStrToBoard;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Dunsany\Board;
use Chess\Variant\Dunsany\FEN\Str;

class StrToBoard extends ClassicalFenStrToBoard
{
    public function __construct(string $string)
    {
        $this->square = new Square();
        $this->fenStr = new Str();
        $this->string = $this->fenStr->validate($string);
        $this->fields = array_filter(explode(' ', $this->string));
        $this->castlingAbility = $this->fields[2];
        $this->castlingRule = new CastlingRule();
        $this->namespace = 'Dunsany';
    }

    public function create(): AbstractBoard
    {
        try {
            $pieces = PieceArrayFactory::create(
                $this->fenStr->toArray($this->fields[0]),
                $this->square,
                $this->castlingRule,
                $this->namespace
            );
            $board = new Board($pieces, $this->castlingAbility);
            $board->turn = $this->fields[1];
            $board->startFen = $this->string;
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $this->enPassant($board);
    }
}
