<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\DefenseEvaluation;
use Chess\Evaluation\PressureEvaluation;
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
    private $defenseEvald;

    /**
     * Pressure evaluation containing the squares being pressured.
     *
     * @var array
     */
    private $pressEvald;

    /**
     * @param \Chess\Board $board
     */
    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->defenseEvald = (new DefenseEvaluation($board))->evaluate();
        $this->pressEvald = (new PressureEvaluation($board))->evaluate();

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
    public function evaluate(): array
    {
        foreach ($this->target as $color => $squares) {
            foreach ($squares as $square) {
                $identity = $this->board->getPieceByPosition($square)->getIdentity();
                if ($identity !== Symbol::KING) {
                    $this->result[$color] += $this->value[$identity];
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
        foreach ($this->pressEvald as $color => $squares) {
            $countPress = array_count_values($squares);
            $countDefense = array_count_values($this->defenseEvald[Symbol::oppColor($color)]);
            foreach ($squares as $square) {
                if (in_array($square, $this->defenseEvald[Symbol::oppColor($color)])) {
                    if ($countPress[$square] > $countDefense[$square]) {
                        $this->target[$color][] = $square;
                    }
                } else {
                    $this->target[$color][] = $square;
                }
            }
        }
    }
}
