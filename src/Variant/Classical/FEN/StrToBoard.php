<?php

namespace Chess\Variant\Classical\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArray;
use Chess\Variant\VariantType;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\CastlingRule;
use Chess\Variant\Classical\FEN\Str;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class StrToBoard
{
    protected Square $square;

    protected Str $fenStr;

    protected string $string;

    protected array $fields;

    protected string $castlingAbility = '-';

    protected ?CastlingRule $castlingRule = null;

    protected string $variant;

    public function __construct(string $string)
    {
        $this->square = new Square();
        $this->fenStr = new Str();
        $this->string = $this->fenStr->validate($string);
        $this->fields = array_filter(explode(' ', $this->string));
        $this->castlingAbility = $this->fields[2];
        $this->castlingRule = new CastlingRule();
        $this->variant = VariantType::CLASSICAL;
    }

    public function create(): AbstractBoard
    {
        try {
            $pieces = (new PieceArray(
                $this->fenStr->toArray($this->fields[0]),
                $this->square,
                $this->castlingRule,
                $this->variant
            ))->pieces;
            $board = new Board($pieces, $this->castlingAbility);
            $board->turn = $this->fields[1];
            $board->startFen = $this->string;
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $this->enPassant($board);
    }

    protected function enPassant(AbstractBoard $board): AbstractBoard
    {
        if ($this->fields[3] !== '-') {
            foreach ($board->pieces($this->fields[1]) as $piece) {
                if ($piece->id === Piece::P) {
                    if (in_array($this->fields[3], $piece->xSqs)) {
                        $piece->xEnPassantSq = $this->fields[3];
                    }
                }
            }
        }

        return $board;
    }
}
