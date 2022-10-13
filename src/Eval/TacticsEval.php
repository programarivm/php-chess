<?php

namespace Chess\Eval;

use Chess\Eval\DefenseEval;
use Chess\Eval\PressureEval;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Board;

/**
 * Tactics evaluation.
 *
 * Total piece value obtained from the squares that are not being defended.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class TacticsEval extends AbstractEval
{
    const NAME = 'Tactics';

    /**
     * Defense evaluation containing the defended squares.
     *
     * @var array
     */
    private array $defenseEval;

    /**
     * Pressure evaluation containing the squares being pressured.
     *
     * @var array
     */
    private array $pressEval;

    /**
     * @var array[]
     */
    private array $target;

    /**
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->defenseEval = (new DefenseEval($board))->eval();
        $this->pressEval = (new PressureEval($board))->eval();

        $this->target = [
            Color::W => [],
            Color::B => [],
        ];

        $this->result = [
            Color::W => 0,
            Color::B => 0,
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
                if ($id !== Piece::K) {
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
    protected function target(): void
    {
        foreach ($this->pressEval as $color => $sqs) {
            $countPress = array_count_values($sqs);
            $countDefense = array_count_values($this->defenseEval[Color::opp($color)]);
            foreach ($sqs as $sq) {
                if (in_array($sq, $this->defenseEval[Color::opp($color)])) {
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
