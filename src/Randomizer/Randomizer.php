<?php

namespace Chess\Randomizer;

use Chess\Piece\K;
use Chess\Piece\RType;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Randomizer.
 *
 * Creates a chess board object with random pieces.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class Randomizer
{
    /**
     * Chess board.
     *
     * @var \Chess\Variant\Classical\Board
     */
    protected Board $board;

    /**
     * Constructor.
     *
     * @param string $turn
     * @param array $items
     */
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

    /**
     * Returns the board.
     *
     * @return \Chess\Variant\Classical\Board
     */
    public function getBoard(): Board
    {
        return $this->board;
    }

    /**
     * Returns a random square.
     *
     * @return string
     */
    protected function sq(): string
    {
        $sqs = $this->board->square->all();
        shuffle($sqs);

        return $sqs[0];
    }

    /**
     * Finds out if two squares are adjacent to each other.
     *
     * @return bool
     */
    protected function areAdjacentSqs(string $w, string $b): bool
    {
        $prev = chr(ord($w) - 1);
        $curr = $w;
        $next = chr(ord($w) + 1);

        return $b === $prev || $b === $curr || $b === $next;
    }

    /**
     * Creates a board with two randomly placed kings.
     *
     * @return array
     */
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

    /**
     * Adds items randomly to the given array of pieces.
     *
     * @param array $items
     * @param array $pieces
     * @return array
     */
    protected function rand(array $items, array $pieces): array
    {
        $freeSqs = $this->board->sqCount->free;
        foreach ($items as $color => $ids) {
            foreach ($ids as $id) {
                $arrayRand = array_rand($freeSqs, 1);
                $sq = $freeSqs[$arrayRand];
                $className = "\Chess\\Piece\\$id";
                $pieces[] = new $className(
                    $color,
                    $sq,
                    $this->board->square,
                    $id !== Piece::R ?: RType::PROMOTED
                );
                unset($freeSqs[$arrayRand]);
            }
        }

        return $pieces;
    }

    /**
     * Finds out if any of the two kings is being attacked.
     *
     * @return bool
     */
    protected function isAttackingKing(Board $board): bool
    {
        foreach ($board->getPieces() as $piece) {
            if ($piece->isAttackingKing()) {
                return true;
            }
        }

        return false;
    }
}
