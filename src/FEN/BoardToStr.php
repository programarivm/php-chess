<?php

namespace Chess\FEN;

use Chess\Ascii;
use Chess\Board;
use Chess\PGN\Symbol;

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
        $array = (new Ascii())->toArray($this->board);
        for ($i = 7; $i >= 0; $i--) {
            $string .= str_replace(' ', '', implode('', $array[$i]));
            if ($i != 0) {
                $string .= '/';
            }
        }

        return "{$this->filter($string)} {$this->board->getTurn()} {$this->castleRights()} {$this->enPassant()}";
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

    private function castleRights()
    {
        $castleRights = '';
        $castle = $this->board->getCastle();
        if ($castle[Symbol::WHITE][Symbol::O_O]) {
            $castleRights .= 'K';
        }
        if ($castle[Symbol::WHITE][Symbol::O_O_O]) {
            $castleRights .= 'Q';
        }
        if ($castle[Symbol::BLACK][Symbol::O_O]) {
            $castleRights .= 'k';
        }
        if ($castle[Symbol::BLACK][Symbol::O_O_O]) {
            $castleRights .= 'q';
        }
        if ($castleRights === '') {
            $castleRights = '-';
        }

        return $castleRights;
    }

    private function enPassant()
    {
        $history = $this->board->getHistory();
        if ($history) {
            $last = array_slice($history, -1)[0];
            if ($last->move->id === Symbol::P) {
                $prev = $last->sq;
                $next = $last->move->sq->next;
                if ($last->move->color === Symbol::WHITE) {
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
