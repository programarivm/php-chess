<?php

namespace Chess\FEN;

use Chess\Ascii;
use Chess\Board;
use Chess\Pieces;
use Chess\Exception\UnknownNotationException;
use Chess\PGN\AN\Color;

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
    private string $string;

    private array $fields;

    private string $castlingAbility;

    public function __construct(string $string)
    {
        $this->string = Str::validate($string);

        $this->fields = array_filter(explode(' ', $this->string));

        $this->castlingAbility = $this->fields[2];
    }

    public function create(): Board
    {
        try {
            $pieces = (new Pieces())->fen($this->fields[0])->getPieces();
            $board = (new Board($pieces, $this->castlingAbility))
                ->setTurn($this->fields[1]);
            if ($this->fields[3] !== '-') {
                $board = $this->doublePawnPush($board);
            }
        } catch (\Throwable $e) {
            throw new UnknownNotationException;
        }

        return $board;
    }

    protected function doublePawnPush(Board $board)
    {
        $array = Ascii::toArray($board);
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
        Ascii::setArrayElem($piece, $fromSquare, $array)
            ->setArrayElem(' . ', $toSquare, $array);
        $board = Ascii::toBoard($array, $turn, $board->getCastlingAbility());
        $board->play($turn, $toSquare);

        return $board;
    }
}
