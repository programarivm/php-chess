<?php

namespace Chess;

use Chess\EvalFunction;
use Chess\HeuristicsByFen;
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
    /**
     * The evaluation function.
     *
     * @var \Chess\EvalFunction
     */
    protected EvalFunction $evalFunction;

    /**
     * The balanced evaluations.
     *
     * @var array
     */
    protected array $balance = [];

    /**
     * Constructor.
     *
     * @param string $movetext
     * @param \Chess\Variant\Classical\Board|null $board
     */
    public function __construct(string $movetext = '', Board $board = null)
    {
        parent::__construct($movetext, $board);

        $this->evalFunction = new EvalFunction();

        $this->calc()->normalize();
    }

    /**
     * Returns the balance.
     *
     * @return array
     */
    public function getBalance(): array
    {
        return $this->balance;
    }

    /**
     * Calculates the evaluation.
     *
     * @return \Chess\Heuristics
     */
    protected function calc(): Heuristics
    {
        foreach ($this->sanMovetext->getMoves() as $key => $val) {
            if ($val !== Move::ELLIPSIS) {
                $turn = $this->board->getTurn();
                if ($this->board->play($turn, $val)) {
                    $this->balance[] = (new HeuristicsByFen($this->board->toFen()))
                        ->getBalance();
                    if (!empty($this->sanMovetext->getMoves()[$key+1])) {
                        $this->board->play(
                            Color::opp($turn),
                            $this->sanMovetext->getMoves()[$key+1]
                        );
                    }
                    $this->balance[] = (new HeuristicsByFen($this->board->toFen()))
                        ->getBalance();
                }
            }
        }

        return $this;
    }

    /**
     * Normalizes the evaluation.
     *
     * @return \Chess\Heuristics
     */
    protected function normalize(): Heuristics
    {
        if ($this->balance) {
            $columns = [];
            $mins = [];
            $maxs = [];
            $normd = [];
            $transpose = [];
            for ($i = 0; $i < count($this->evalFunction->getEval()); $i++) {
                $columns[$i] = array_column($this->balance, $i);
                $mins[$i] = round(min($columns[$i]), 2);
                $maxs[$i] = round(max($columns[$i]), 2);
            }
            for ($i = 0; $i < count($this->evalFunction->getEval()); $i++) {
                for ($j = 0; $j < count($columns[$i]); $j++) {
                    if ($maxs[$i] - $mins[$i] > 0) {
                        $normd[$i][$j] = round(($columns[$i][$j] - $mins[$i]) / ($maxs[$i] - $mins[$i]), 2);
                    } elseif ($maxs[$i] == $mins[$i]) {
                        $normd[$i][$j] = 0;
                    }
                }
            }
            for ($i = 0; $i < count($normd); $i++) {
                for ($j = 0; $j < count($normd[$i]); $j++) {
                    $transpose[$j][$i] = $normd[$i][$j];
                }
            }
            $this->balance = $transpose;
        }

        return $this;
    }
}
