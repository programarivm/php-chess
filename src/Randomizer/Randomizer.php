<?php

namespace Chess\Randomizer;

use Chess\Variant\RType;
use Chess\Variant\VariantType;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\K;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

class Randomizer
{
    public Board $board;

    public function __construct(string $turn, array $items = [])
    {
        $this->board = new Board();
        do {
            $pieces = $this->rand($items, $this->kings());
            $board = new Board($pieces);
        } while ($this->isAttackingKing($board));

        $board->turn = $turn;

        $this->board = $board;
    }

    protected function sq(): string
    {
        $sqs = $this->board->square->all;
        shuffle($sqs);

        return $sqs[0];
    }

    protected function areAdjacentSqs(string $w, string $b): bool
    {
        $prev = chr(ord($w) - 1);
        $curr = $w;
        $next = chr(ord($w) + 1);

        return $b === $prev || $b === $curr || $b === $next;
    }

    protected function kings(): array
    {
        $wSq = $this->sq();
        $wFile = $wSq[0];
        $wRank = $wSq[1];

        do {
            $bSq = $this->sq();
            $bFile = $bSq[0];
            $bRank = $bSq[1];
        } while (
            $this->areAdjacentSqs($wFile, $bFile) &&
            $this->areAdjacentSqs($wRank, $bRank)
        );

        $pieces = [
            new K(Color::W, $wSq, $this->board->square),
            new K(Color::B, $bSq, $this->board->square),
        ];

        $this->board = new Board($pieces);

        return $pieces;
    }

    protected function rand(array $items, array $pieces): array
    {
        $freeSqs = $this->board->sqCount['free'];
        foreach ($items as $color => $ids) {
            foreach ($ids as $id) {
                $arrayRand = array_rand($freeSqs, 1);
                $sq = $freeSqs[$arrayRand];
                $class = VariantType::getClass($id);
                $pieces[] = new $class(
                    $color,
                    $sq,
                    $this->board->square,
                    $id !== Piece::R ?: RType::R
                );
                unset($freeSqs[$arrayRand]);
            }
        }

        return $pieces;
    }

    protected function isAttackingKing(Board $board): bool
    {
        foreach ($board->pieces() as $piece) {
            if ($piece->isAttackingKing()) {
                return true;
            }
        }

        return false;
    }
}
