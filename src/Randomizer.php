<?php

namespace Chess;

use Chess\PGN\AN\Color;
use Chess\Piece\B;
use Chess\Piece\K;
use Chess\Piece\N;
use Chess\Piece\P;
use Chess\Piece\Q;
use Chess\Piece\R;
use Chess\Piece\RType;

class Randomizer
{
    const FILES = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];

    const RANKS = ['1', '2', '3', '4', '5', '6', '7', '8'];

    private Board $board;

    private array $pieces = [];

    private string $castlingAbility;

    public function __construct()
    {
        $this->kings();
    }

    public function getBoard()
    {
        return $this->board;
    }

    private function sq(): string
    {
        $files = self::FILES;
        $ranks = self::RANKS;

        shuffle($files);
        shuffle($ranks);

        $file = $files[0];
        $rank = $ranks[0];

        return $file . $rank;
    }

    private function isAdjacent(string $w, string $b): bool
    {
        $prev = chr(ord($w) - 1);
        $curr = $w;
        $next = chr(ord($w) + 1);

        return $b === $prev || $b === $curr || $b === $next;
    }

    private function kings()
    {
        $wSq = $this->sq();
        $wFile = $wSq[0];
        $wRank = $wSq[1];

        do {
            $bSq = $this->sq();
            $bFile = $bSq[0];
            $bRank = $bSq[1];
        } while (
            $this->isAdjacent($wFile, $bFile) &&
            $this->isAdjacent($wRank, $bRank)
        );

        $this->pieces = [
            new K(Color::W, $wSq),
            new K(Color::B, $bSq)
        ];

        $this->castlingAbility = '-';

        $this->board = new Board($this->pieces, $this->castlingAbility);

        return $this;
    }

    public function pieces(array $items)
    {
        $pieces = [];
        $freeSqs = $this->board->getSqEval()->free;
        foreach ($items as $color => $ids) {
            foreach ($ids as $id) {
                do {
                    $sq = $this->sq();
                } while (!in_array($sq, $freeSqs));
                $pieces[] = [
                    'color' => $color,
                    'id' => $id,
                    'sq' => $sq,
                ];
                if (($key = array_search($sq, $freeSqs)) !== false) {
                    unset($freeSqs[$key]);
                }
            }
        }

        // TODO...

        return $this;
    }
}
