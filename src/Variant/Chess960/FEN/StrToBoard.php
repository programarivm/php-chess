<?php

namespace Chess\Variant\Chess960\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArray;
use Chess\Variant\VariantType;
use Chess\Variant\Chess960\Board;
use Chess\Variant\Chess960\Rule\CastlingRule;
use Chess\Variant\Classical\FEN\Str;
use Chess\Variant\Classical\FEN\StrToBoard as ClassicalFenStrToBoard;
use Chess\Variant\Classical\PGN\AN\Square;

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
        $this->pieceVariant = VariantType::CLASSICAL;
    }

    public function create(): AbstractBoard
    {
        try {
            $pieces = (new PieceArray(
                $this->fenStr->toArray($this->fields[0]),
                $this->square,
                $this->castlingRule,
                $this->pieceVariant
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
