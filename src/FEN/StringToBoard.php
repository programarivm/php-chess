<?php

namespace Chess\FEN;

use Chess\Ascii;
use Chess\Board;
use Chess\Castling;
use Chess\Exception\UnknownNotationException;
use Chess\PGN\Symbol;
use Chess\Piece\Bishop;
use Chess\Piece\King;
use Chess\Piece\Knight;
use Chess\Piece\Pawn;
use Chess\Piece\Queen;
use Chess\Piece\Rook;
use Chess\Piece\Type\RookType;

/**
 * FEN string to Chess\Board converter.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class StringToBoard
{
    private $string;

    private $fields;

    private $castling;

    private $pieces;

    public function __construct(string $string)
    {
        $this->string = Validate::fen($string);

        $this->fields = array_filter(explode(' ', $this->string));

        $this->castling = Castling::$initialState;

        $this->pieces = [];

        $this->castling();
    }

    public function create(): Board
    {
        try {
            $fields = array_filter(explode('/', $this->fields[0]));
            foreach ($fields as $key => $field) {
                $file = 'a';
                $rank = 8 - $key;
                foreach (str_split($field) as $char) {
                    if (ctype_lower($char)) {
                        $char = strtoupper($char);
                        $this->pushPiece(Symbol::BLACK, $char, $file.$rank);
                        $file = chr(ord($file) + 1);
                    } elseif (ctype_upper($char)) {
                        $this->pushPiece(Symbol::WHITE, $char, $file.$rank);
                        $file = chr(ord($file) + 1);
                    } elseif (is_numeric($char)) {
                        $file = chr(ord($file) + $char);
                    }
                }
            }
            $board = (new Board($this->pieces, $this->castling))
                ->setTurn($this->fields[1]);

            if ($this->fields[3] !== '-') {
                $board = $this->doublePawnPush($board);
            }
        } catch (\Throwable $e) {
            throw new UnknownNotationException(
                "A chessboard cannot be created from this FEN string."
            );
        }

        return $board;
    }

    private function castling()
    {
        switch (true) {
            case $this->fields[2] === '-':
                $this->castling[Symbol::WHITE][Symbol::CASTLING_SHORT] = false;
                $this->castling[Symbol::WHITE][Symbol::CASTLING_LONG] = false;
                $this->castling[Symbol::BLACK][Symbol::CASTLING_SHORT] = false;
                $this->castling[Symbol::BLACK][Symbol::CASTLING_LONG] = false;
                break;
            case !str_contains($this->fields[2], 'K') && !str_contains($this->fields[2], 'Q'):
                $this->castling[Symbol::WHITE][Symbol::CASTLING_SHORT] = false;
                $this->castling[Symbol::WHITE][Symbol::CASTLING_LONG] = false;
                break;
            case !str_contains($this->fields[2], 'K'):
                $this->castling[Symbol::WHITE][Symbol::CASTLING_SHORT] = false;
                break;
            case !str_contains($this->fields[2], 'Q'):
                $this->castling[Symbol::WHITE][Symbol::CASTLING_LONG] = false;
                break;
            case !str_contains($this->fields[2], 'k') && !str_contains($this->fields[2], 'q'):
                $this->castling[Symbol::BLACK][Symbol::CASTLING_SHORT] = false;
                $this->castling[Symbol::BLACK][Symbol::CASTLING_LONG] = false;
                break;
            case !str_contains($this->fields[2], 'k'):
                $this->castling[Symbol::BLACK][Symbol::CASTLING_SHORT] = false;
                break;
            case !str_contains($this->fields[2], 'q'):
                $this->castling[Symbol::BLACK][Symbol::CASTLING_LONG] = false;
                break;
            default:
                // do nothing
                break;
        }
    }

    private function pushPiece($color, $char, $sq)
    {
        switch ($char) {
            case Symbol::KING:
                $this->pieces[] = new King($color, $sq);
                break;
            case Symbol::QUEEN:
                $this->pieces[] = new Queen($color, $sq);
                break;
            case Symbol::ROOK:
                if ($color === Symbol::BLACK &&
                    $sq === 'a8' &&
                    $this->castling[$color][Symbol::CASTLING_LONG]
                ) {
                    $this->pieces[] = new Rook($color, $sq, RookType::CASTLING_LONG);
                } elseif (
                    $color === Symbol::BLACK &&
                    $sq === 'h8' &&
                    $this->castling[$color][Symbol::CASTLING_SHORT]
                ) {
                    $this->pieces[] = new Rook($color, $sq, RookType::CASTLING_SHORT);
                } elseif (
                    $color === Symbol::WHITE &&
                    $sq === 'a1' &&
                    $this->castling[$color][Symbol::CASTLING_LONG]
                ) {
                    $this->pieces[] = new Rook($color, $sq, RookType::CASTLING_LONG);
                } elseif (
                    $color === Symbol::WHITE &&
                    $sq === 'h1' &&
                    $this->castling[$color][Symbol::CASTLING_SHORT]
                ) {
                    $this->pieces[] = new Rook($color, $sq, RookType::CASTLING_SHORT);
                } else {
                    // in this case it really doesn't matter which RookType is assigned to the rook
                    $this->pieces[] = new Rook($color, $sq, RookType::CASTLING_LONG);
                }
                break;
            case Symbol::BISHOP:
                $this->pieces[] = new Bishop($color, $sq);
                break;
            case Symbol::KNIGHT:
                $this->pieces[] = new Knight($color, $sq);
                break;
            case Symbol::PAWN:
                $this->pieces[] = new Pawn($color, $sq);
                break;
            default:
                // do nothing
                break;
        }
    }

    protected function doublePawnPush(Board $board)
    {
        $ascii = new Ascii();
        $array = $ascii->toArray($board);
        $file = $this->fields[3][0];
        $rank = $this->fields[3][1];
        if ($this->fields[1] === Symbol::WHITE) {
            $piece = ' p ';
            $fromRank = $rank + 1;
            $toRank = $rank - 1;
            $turn = Symbol::BLACK;
        } else {
            $piece = ' P ';
            $fromRank = $rank - 1;
            $toRank = $rank + 1;
            $turn = Symbol::WHITE;
        }
        $fromSquare = $file.$fromRank;
        $toSquare = $file.$toRank;
        $ascii->setArrayElem($piece, $fromSquare, $array)
            ->setArrayElem(' . ', $toSquare, $array);
        $board = $ascii->toBoard($array, $turn, $board->getCastling());
        $board->play($turn, $toSquare);

        return $board;
    }
}
