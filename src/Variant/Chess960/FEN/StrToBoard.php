<?php

namespace Chess\Variant\Chess960\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Piece\AsciiArray;
use Chess\Piece\PieceArray;
use Chess\Variant\Chess960\Board;
use Chess\Variant\Chess960\Rule\CastlingRule;
use Chess\Variant\Classical\FEN\Str;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

/**
 * StrToBoard
 *
 * Converts a FEN string to a chessboard object.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class StrToBoard
{
    protected array $size;

    protected Str $fenStr;

    protected string $string;

    protected array $fields;

    protected string $castlingAbility;

    private array $startPos;

    protected array $castlingRule;

    public function __construct(string $string, array $startPos)
    {
        $this->size = Square::SIZE;
        $this->fenStr = new Str();
        $this->string = $this->fenStr->validate($string);
        $this->fields = array_filter(explode(' ', $this->string));
        $this->castlingAbility = $this->fields[2];
        $this->startPos = $startPos;
        $this->castlingRule =  (new CastlingRule($this->startPos))->getRule();
    }

    public function create(): Board
    {
        try {
            $pieces = (new PieceArray(
                $this->fenStr->toAsciiArray($this->fields[0]),
                $this->size,
                $this->castlingRule
            ))->getArray();
            $board = (new Board($this->startPos, $pieces, $this->castlingAbility))
                ->setTurn($this->fields[1]);
            if ($this->fields[3] !== '-') {
                $board = $this->doublePawnPush($board);
            }
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $board;
    }

    protected function doublePawnPush(Board $board)
    {
        $file = $this->fields[3][0];
        $rank = intval(substr($this->fields[3], 1));
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
        $fromSq = $file.$fromRank;
        $toSq = $file.$toRank;
        $board = (new AsciiArray($board->toAsciiArray(), $this->size, $this->castlingRule))
            ->setElem($piece, $fromSq)
            ->setElem(' . ', $toSq)
            ->toChess960Board(get_class($board), $turn, $this->castlingAbility, $this->startPos);
        $board->play($turn, $toSq);

        return $board;
    }
}
