<?php

namespace Chess\FEN;

use Chess\Ascii;
use Chess\Board;
use Chess\PGN\Symbol;
use Chess\Castling\Rule as CastlingRule;

/**
 * Chess\Board to FEN string converter.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class BoardToString
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

        return "{$this->filter($string)} {$this->board->getTurn()} {$this->castlingRights()} {$this->enPassant()}";
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

    private function castlingRights()
    {
        $castlingRights = '';
        $castling = $this->board->getCastling();
        if ($castling[Symbol::WHITE][Symbol::CASTLING_SHORT]) {
            $castlingRights .= 'K';
        }
        if ($castling[Symbol::WHITE][Symbol::CASTLING_LONG]) {
            $castlingRights .= 'Q';
        }
        if ($castling[Symbol::BLACK][Symbol::CASTLING_SHORT]) {
            $castlingRights .= 'k';
        }
        if ($castling[Symbol::BLACK][Symbol::CASTLING_LONG]) {
            $castlingRights .= 'q';
        }
        if ($castlingRights === '') {
            $castlingRights = '-';
        }

        return $castlingRights;
    }

    private function enPassant()
    {
        $history = $this->board->getHistory();
        if ($history) {
            $last = array_slice($history, -1)[0];
            if ($last->move->identity === Symbol::PAWN) {
                $prev = $last->position;
                $next = $last->move->position->next;
                if ($last->move->color === Symbol::WHITE) {
                    if ($last->move->position->next[1] - $last->position[1] === 2) {
                        $rank = $last->position[1] + 1;
                        return $last->move->position->current.$rank;
                    }
                } else {
                    if ($last->position[1] - $last->move->position->next[1] === 2) {
                        $rank = $last->position[1] - 1;
                        return $last->move->position->current.$rank;
                    }
                }
            }
        }

        return '-';
    }
}
