<?php

namespace Chess\FEN;

use Chess\Board;
use Chess\PGN\AN\Castle;
use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;

/**
 * BoardToStr
 *
 * Converts a Chess\Board object to a FEN string.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class BoardToStr
{
    private $board;

    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    public function create(): string
    {
        $string = '';
        $array = $this->board->toAsciiArray();
        for ($i = 7; $i >= 0; $i--) {
            $string .= str_replace(' ', '', implode('', $array[$i]));
            if ($i != 0) {
                $string .= '/';
            }
        }

        return "{$this->filter($string)} {$this->board->getTurn()} {$this->board->getCastlingAbility()} {$this->enPassant()}";
    }

    private function filter(string $string)
    {
        $filtered = '';
        $strSplit = str_split($string);
        $n = 1;
        for ($i = 0; $i < count($strSplit); $i++) {
            if ($strSplit[$i] === '.') {
                if (isset($strSplit[$i+1]) && $strSplit[$i+1] === '.') {
                    $n++;
                } else {
                    $filtered .= $n;
                    $n = 1;
                }
            } else {
                $filtered .= $strSplit[$i];
                $n = 1;
            }
        }

        return $filtered;
    }

    private function enPassant()
    {
        $history = $this->board->getHistory();
        if ($history) {
            $last = array_slice($history, -1)[0];
            if ($last->move->id === Piece::P) {
                $prev = $last->sq;
                $next = $last->move->sq->next;
                if ($last->move->color === Color::W) {
                    if ($last->move->sq->next[1] - $last->sq[1] === 2) {
                        $rank = $last->sq[1] + 1;
                        return $last->move->sq->current.$rank;
                    }
                } else {
                    if ($last->sq[1] - $last->move->sq->next[1] === 2) {
                        $rank = $last->sq[1] - 1;
                        return $last->move->sq->current.$rank;
                    }
                }
            }
        }

        return '-';
    }
}
