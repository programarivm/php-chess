<?php

namespace Chess\Variant\Classical\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Piece\PieceArray;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\FEN\Str;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

/**
 * StrToBoard
 *
 * Converts a FEN string to a chessboard object.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class StrToBoard
{
    protected Square $square;

    protected Str $fenStr;

    protected string $string;

    protected array $fields;

    protected string $castlingAbility;

    protected CastlingRule $castlingRule;

    public function __construct(string $string)
    {
        $this->square = new Square();
        $this->fenStr = new Str();
        $this->string = $this->fenStr->validate($string);
        $this->fields = array_filter(explode(' ', $this->string));
        $this->castlingAbility = $this->fields[2];
        $this->castlingRule = new CastlingRule();
    }

    public function create(): Board
    {
        try {
            $pieces = (new PieceArray(
                $this->fenStr->toAsciiArray($this->fields[0]),
                $this->square,
                $this->castlingRule
            ))->getArray();
            $board = new Board($pieces, $this->castlingAbility);
            $board->turn = $this->fields[1];
            $board->startFen = $this->string;
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $this->enPassant($board);
    }

    protected function enPassant(Board $board): Board
    {
        if ($this->fields[3] !== '-') {
            foreach ($pieces = $board->getPieces($this->fields[1]) as $piece) {
                if ($piece->id === Piece::P) {
                    if (in_array($this->fields[3], $piece->getCaptureSqs())) {
                        $piece->setEnPassantSq($this->fields[3]);
                    }
                }
            }
        }

        return $board;
    }
}
