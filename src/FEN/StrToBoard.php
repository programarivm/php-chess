<?php

namespace Chess\FEN;

use Chess\Ascii;
use Chess\Board;
use Chess\Castle;
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
 * StrToBoard
 *
 * Converts a FEN string to a Chess\Board object.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class StrToBoard
{
    private $string;

    private $fields;

    private $castle;

    private $pieces;

    public function __construct(string $string)
    {
        $this->string = Validate::fen($string);

        $this->fields = array_filter(explode(' ', $this->string));

        $this->castle = Castle::$initialState;

        $this->pieces = [];

        $this->castle();
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
            $board = (new Board($this->pieces, $this->castle))
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

    private function castle()
    {
        switch (true) {
            case $this->fields[2] === '-':
                $this->castle[Symbol::WHITE][Symbol::O_O] = false;
                $this->castle[Symbol::WHITE][Symbol::O_O_O] = false;
                $this->castle[Symbol::BLACK][Symbol::O_O] = false;
                $this->castle[Symbol::BLACK][Symbol::O_O_O] = false;
                break;
            case !str_contains($this->fields[2], 'K') && !str_contains($this->fields[2], 'Q'):
                $this->castle[Symbol::WHITE][Symbol::O_O] = false;
                $this->castle[Symbol::WHITE][Symbol::O_O_O] = false;
                break;
            case !str_contains($this->fields[2], 'K'):
                $this->castle[Symbol::WHITE][Symbol::O_O] = false;
                break;
            case !str_contains($this->fields[2], 'Q'):
                $this->castle[Symbol::WHITE][Symbol::O_O_O] = false;
                break;
            case !str_contains($this->fields[2], 'k') && !str_contains($this->fields[2], 'q'):
                $this->castle[Symbol::BLACK][Symbol::O_O] = false;
                $this->castle[Symbol::BLACK][Symbol::O_O_O] = false;
                break;
            case !str_contains($this->fields[2], 'k'):
                $this->castle[Symbol::BLACK][Symbol::O_O] = false;
                break;
            case !str_contains($this->fields[2], 'q'):
                $this->castle[Symbol::BLACK][Symbol::O_O_O] = false;
                break;
            default:
                // do nothing
                break;
        }
    }

    private function pushPiece($color, $char, $sq)
    {
        switch ($char) {
            case Symbol::K:
                $this->pieces[] = new King($color, $sq);
                break;
            case Symbol::Q:
                $this->pieces[] = new Queen($color, $sq);
                break;
            case Symbol::R:
                if ($color === Symbol::BLACK &&
                    $sq === 'a8' &&
                    $this->castle[$color][Symbol::O_O_O]
                ) {
                    $this->pieces[] = new Rook($color, $sq, RookType::O_O_O);
                } elseif (
                    $color === Symbol::BLACK &&
                    $sq === 'h8' &&
                    $this->castle[$color][Symbol::O_O]
                ) {
                    $this->pieces[] = new Rook($color, $sq, RookType::O_O);
                } elseif (
                    $color === Symbol::WHITE &&
                    $sq === 'a1' &&
                    $this->castle[$color][Symbol::O_O_O]
                ) {
                    $this->pieces[] = new Rook($color, $sq, RookType::O_O_O);
                } elseif (
                    $color === Symbol::WHITE &&
                    $sq === 'h1' &&
                    $this->castle[$color][Symbol::O_O]
                ) {
                    $this->pieces[] = new Rook($color, $sq, RookType::O_O);
                } else {
                    // in this case it really doesn't matter which RookType is assigned to the rook
                    $this->pieces[] = new Rook($color, $sq, RookType::O_O_O);
                }
                break;
            case Symbol::B:
                $this->pieces[] = new Bishop($color, $sq);
                break;
            case Symbol::N:
                $this->pieces[] = new Knight($color, $sq);
                break;
            case Symbol::P:
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
        $board = $ascii->toBoard($array, $turn, $board->getCastle());
        $board->play($turn, $toSquare);

        return $board;
    }
}
