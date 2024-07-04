<?php

namespace Chess\Variant\CapablancaFischer\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Piece\PieceArray;
use Chess\Piece\VariantType;
use Chess\Variant\AbstractBoard;
use Chess\Variant\CapablancaFischer\Board;
use Chess\Variant\CapablancaFischer\Rule\CastlingRule;
use Chess\Variant\Capablanca\FEN\Str;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Classical\FEN\StrToBoard as ClassicalFenStrToBoard;

class StrToBoard extends ClassicalFenStrToBoard
{
    private array $startPos;

    public function __construct(string $string, array $startPos)
    {
        $this->square = new Square();
        $this->fenStr = new Str();
        $this->string = $this->fenStr->validate($string);
        $this->fields = array_filter(explode(' ', $this->string));
        $this->castlingAbility = $this->fields[2];
        $this->startPos = $startPos;
        $this->castlingRule = new CastlingRule($this->startPos);
        $this->variant = VariantType::CAPABLANCA;
    }

    public function create(): AbstractBoard
    {
        try {
            $pieces = (new PieceArray(
                $this->fenStr->toAsciiArray($this->fields[0]),
                $this->square,
                $this->castlingRule,
                $this->variant
            ))->getArray();
            $board = new Board($this->startPos, $pieces, $this->castlingAbility);
            $board->turn = $this->fields[1];
            $board->startFen = $this->string;
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $this->enPassant($board);
    }
}
