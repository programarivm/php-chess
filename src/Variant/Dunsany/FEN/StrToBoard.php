<?php

namespace Chess\Variant\Dunsany\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArray;
use Chess\Variant\VariantType;
use Chess\Variant\Classical\FEN\StrToBoard as ClassicalFenStrToBoard;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;
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
        $this->pieceVariant = VariantType::CLASSICAL;
    }

    public function create(): AbstractBoard
    {
        try {
            $pieces = (new PieceArray(
                $this->fenStr->toAsciiArray($this->fields[0]),
                $this->square,
                $this->castlingRule,
                $this->pieceVariant
            ))->getArray();
            $board = new Board($pieces, $this->castlingAbility);
            $board->turn = $this->fields[1];
            $board->startFen = $this->string;
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $this->enPassant($board);
    }
}
