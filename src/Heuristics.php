<?php

namespace Chess;

use Chess\HeuristicsByFen;
use Chess\Eval\InverseEvalInterface;
use Chess\Play\SanPlay;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Heuristics
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class Heuristics extends SanPlay
{
    use HeuristicsTrait;

    /**
     * Constructor.
     *
     * @param string $movetext
     * @param Board|null $board
     */
    public function __construct(string $movetext = '', Board $board = null)
    {
        parent::__construct($movetext, $board);

        $this->calc();
    }

    /**
     * Returns the current evaluation.
     *
     * @return array
     */
    public function eval(): array
    {
        return (new HeuristicsByFen($this->board->toFen()))->eval();
    }

    /**
     * Calculates the heuristics.
     *
     * @return \Chess\Heuristics
     */
    protected function calc(): Heuristics
    {
        foreach ($this->sanMovetext->getMoves() as $key => $val) {
            if ($val !== Move::ELLIPSIS) {
                $turn = $this->board->getTurn();
                if ($this->board->play($turn, $val)) {
                    $result = (new HeuristicsByFen($this->board->toFen()))->getResult();
                    $this->result[Color::W][] = $result[Color::W];
                    $this->result[Color::B][] = $result[Color::B];
                    if (!empty($this->sanMovetext->getMoves()[$key+1])) {
                        $this->board->play(
                            Color::opp($turn),
                            $this->sanMovetext->getMoves()[$key+1]
                        );
                    }
                    $result = (new HeuristicsByFen($this->board->toFen()))->getResult();
                    $this->result[Color::W][] = $result[Color::W];
                    $this->result[Color::B][] = $result[Color::B];

                    $this->balance[] = (new HeuristicsByFen($this->board->toFen()))->getBalance();
                }
            }
        }

        return $this;
    }

    /**
     * Returns the last element in the result.
     *
     * @return array
     */
    public function end(): array
    {
        return [
            Color::W => end($this->result[Color::W]),
            Color::B => end($this->result[Color::B]),
        ];
    }
}
