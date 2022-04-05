<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\DefenseEvaluation;
use Chess\Evaluation\PressureEvaluation;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;

/**
 * Tactics evaluation.
 *
 * Total piece value obtained from the squares that are not being defended.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class TacticsEvaluation extends AbstractEvaluation
{
    const NAME = 'tactics';

    /**
     * Defense evaluation containing the defended squares.
     *
     * @var array
     */
    private $defenseEval;

    /**
     * Pressure evaluation containing the squares being pressured.
     *
     * @var array
     */
    private $pressEval;

    /**
     * @param \Chess\Board $board
     */
    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->defenseEval = (new DefenseEvaluation($board))->eval();
        $this->pressEval = (new PressureEvaluation($board))->eval();

        $this->target = [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];

        $this->target();
    }

    /**
     * Returns the value obtained from the squares that are not being defended.
     *
     * @return array
     */
    public function eval(): array
    {
        foreach ($this->target as $color => $sqs) {
            foreach ($sqs as $sq) {
                $id = $this->board->getPieceBySq($sq)->getId();
                if ($id !== Symbol::K) {
                    $this->result[$color] += $this->value[$id];
                }
            }
        }

        return $this->result;
    }

    /**
     * Calculates the squares that are not being defended.
     *
     * @return array
     */
    protected function target()
    {
        foreach ($this->pressEval as $color => $sqs) {
            $countPress = array_count_values($sqs);
            $countDefense = array_count_values($this->defenseEval[Convert::toOpposite($color)]);
            foreach ($sqs as $sq) {
                if (in_array($sq, $this->defenseEval[Convert::toOpposite($color)])) {
                    if ($countPress[$sq] > $countDefense[$sq]) {
                        $this->target[$color][] = $sq;
                    }
                } else {
                    $this->target[$color][] = $sq;
                }
            }
        }
    }
}
