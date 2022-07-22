<?php

namespace Chess;

use Chess\PGN\AN\Color;
use Chess\Piece\K;
use Chess\Piece\RType;

/**
 * Randomizer.
 *
 * Creates a board with random pieces.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Randomizer
{
    const FILES = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];

    const RANKS = ['1', '2', '3', '4', '5', '6', '7', '8'];

    /**
     * Chess board.
     *
     * @var \Chess\Board
     */
    private Board $board;

    /**
     * The items to be added.
     *
     * @var array
     */
    private array $items = [];

    /**
     * Constructor.
     *
     * @param string $turn
     * @param array $items
     */
    public function __construct(string $turn, array $items = [])
    {
        $this->items = $items;

        do {
            $pieces = $this->kings();
            $pieces = $this->rand($items, $pieces);
            $board = new Board($pieces);
        } while ($this->isAttackingKing($board));

        $this->board = $board->setTurn($turn);
    }

    /**
     * Returns the Chess\Board object.
     *
     * @return \Chess\Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Returns a random square.
     *
     * @return string
     */
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

    /**
     * Finds out if two squares are adjacent to each other.
     *
     * @return bool
     */
    private function areAdjacentSqs(string $w, string $b): bool
    {
        $prev = chr(ord($w) - 1);
        $curr = $w;
        $next = chr(ord($w) + 1);

        return $b === $prev || $b === $curr || $b === $next;
    }

    /**
     * Creates a Chess\Board object with two random kings.
     *
     * @return array
     */
    private function kings(): array
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
            new K(Color::W, $wSq),
            new K(Color::B, $bSq)
        ];

        $this->board = new Board($pieces);

        return $pieces;
    }

    /**
     * Adds items randomly to the given pieces.
     *
     * @param array $items
     * @param array $pieces
     * @return array
     */
    private function rand(array $items, array $pieces): array
    {
        $freeSqs = $this->board->getSqEval()->free;
        foreach ($items as $color => $ids) {
            foreach ($ids as $id) {
                $arrayRand = array_rand($freeSqs, 1);
                $sq = $freeSqs[$arrayRand];
                $className = "\Chess\Piece\\$id";
                $pieces[] = new $className($color, $sq, RType::PROMOTED);
                unset($freeSqs[$arrayRand]);
            }
        }

        return $pieces;
    }

    /**
     * Finds out if any of the two kings are being attacked.
     *
     * @return bool
     */
    private function isAttackingKing(Board $board): bool
    {
        foreach ($board->getPieces() as $piece) {
            if ($piece->isAttackingKing()) {
                return true;
            }
        }

        return false;
    }
}
