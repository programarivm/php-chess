<?php

namespace Chess\FEN;

use Chess\Ascii;
use Chess\Board;
use Chess\Exception\UnknownNotationException;
use Chess\FEN\Field\CastlingAbility;
use Chess\PGN\AN\Castle;
use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;
use Chess\Piece\Bishop;
use Chess\Piece\King;
use Chess\Piece\Knight;
use Chess\Piece\Pawn;
use Chess\Piece\Queen;
use Chess\Piece\Rook;
use Chess\Piece\RookType;

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

    private $castlingAbility;

    private $pieces;

    public function __construct(string $string)
    {
        $this->string = Str::validate($string);

        $this->fields = array_filter(explode(' ', $this->string));

        $this->castlingAbility = CastlingAbility::START;

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
                        $this->pushPiece(Color::B, $char, $file.$rank);
                        $file = chr(ord($file) + 1);
                    } elseif (ctype_upper($char)) {
                        $this->pushPiece(Color::W, $char, $file.$rank);
                        $file = chr(ord($file) + 1);
                    } elseif (is_numeric($char)) {
                        $file = chr(ord($file) + $char);
                    }
                }
            }
            $board = (new Board($this->pieces, $this->castlingAbility))
                ->setTurn($this->fields[1]);

            if ($this->fields[3] !== '-') {
                $board = $this->doublePawnPush($board);
            }
        } catch (\Throwable $e) {
            throw new UnknownNotationException;
        }

        return $board;
    }

    /*
    private function castle()
    {
        switch (true) {
            case $this->fields[2] === '-':
                $this->castle[Color::W][Castle::SHORT] = false;
                $this->castle[Color::W][Castle::LONG] = false;
                $this->castle[Color::B][Castle::SHORT] = false;
                $this->castle[Color::B][Castle::LONG] = false;
                break;
            case !str_contains($this->fields[2], 'K') && !str_contains($this->fields[2], 'Q'):
                $this->castle[Color::W][Castle::SHORT] = false;
                $this->castle[Color::W][Castle::LONG] = false;
                break;
            case !str_contains($this->fields[2], 'K'):
                $this->castle[Color::W][Castle::SHORT] = false;
                break;
            case !str_contains($this->fields[2], 'Q'):
                $this->castle[Color::W][Castle::LONG] = false;
                break;
            case !str_contains($this->fields[2], 'k') && !str_contains($this->fields[2], 'q'):
                $this->castle[Color::B][Castle::SHORT] = false;
                $this->castle[Color::B][Castle::LONG] = false;
                break;
            case !str_contains($this->fields[2], 'k'):
                $this->castle[Color::B][Castle::SHORT] = false;
                break;
            case !str_contains($this->fields[2], 'q'):
                $this->castle[Color::B][Castle::LONG] = false;
                break;
            default:
                // do nothing
                break;
        }
    }
    */

    private function pushPiece(string $color, string $char, string $sq)
    {
        switch ($char) {
            case Piece::K:
                $this->pieces[] = new King($color, $sq);
                break;
            case Piece::Q:
                $this->pieces[] = new Queen($color, $sq);
                break;
            case Piece::R:
                if ($color === Color::B &&
                    $sq === 'a8' &&
                    $this->castle[$color][Castle::LONG]
                ) {
                    $this->pieces[] = new Rook($color, $sq, RookType::CASTLE_LONG);
                } elseif (
                    $color === Color::B &&
                    $sq === 'h8' &&
                    $this->castle[$color][Castle::SHORT]
                ) {
                    $this->pieces[] = new Rook($color, $sq, RookType::CASTLE_SHORT);
                } elseif (
                    $color === Color::W &&
                    $sq === 'a1' &&
                    $this->castle[$color][Castle::LONG]
                ) {
                    $this->pieces[] = new Rook($color, $sq, RookType::CASTLE_LONG);
                } elseif (
                    $color === Color::W &&
                    $sq === 'h1' &&
                    $this->castle[$color][Castle::SHORT]
                ) {
                    $this->pieces[] = new Rook($color, $sq, RookType::CASTLE_SHORT);
                } else {
                    // in this case it really doesn't matter which RookType is assigned to the rook
                    $this->pieces[] = new Rook($color, $sq, RookType::CASTLE_LONG);
                }
                break;
            case Piece::B:
                $this->pieces[] = new Bishop($color, $sq);
                break;
            case Piece::N:
                $this->pieces[] = new Knight($color, $sq);
                break;
            case Piece::P:
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
        if ($this->fields[1] === Color::W) {
            $piece = ' p ';
            $fromRank = $rank + 1;
            $toRank = $rank - 1;
            $turn = Color::B;
        } else {
            $piece = ' P ';
            $fromRank = $rank - 1;
            $toRank = $rank + 1;
            $turn = Color::W;
        }
        $fromSquare = $file.$fromRank;
        $toSquare = $file.$toRank;
        $ascii->setArrayElem($piece, $fromSquare, $array)
            ->setArrayElem(' . ', $toSquare, $array);
        $board = $ascii->toBoard($array, $turn, $board->getCastlingAbility());
        $board->play($turn, $toSquare);

        return $board;
    }
}
