<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\SqEvaluation;
use Chess\PGN\Symbol;

/**
 * Pressure evaluation.
 *
 * Squares being threatened at the present moment.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class PressureEvaluation extends AbstractEvaluation
{
    const NAME = 'pressure';

    /**
     * Square evaluation containing the free and used squares.
     *
     * @var array
     */
    private $sqEval;

    /**
     * @param \Chess\Board $board
     */
    public function __construct(Board $board)
    {
        parent::__construct($board);

        $sqEval = new SqEvaluation($board);

        $this->sqEval = [
            SqEvaluation::TYPE_FREE => $sqEval->eval(SqEvaluation::TYPE_FREE),
            SqEvaluation::TYPE_USED => $sqEval->eval(SqEvaluation::TYPE_USED),
        ];

        $this->result = [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];
    }

    /**
     * Returns the squares being threatened at the present moment.
     *
     * @return array
     */
    public function eval(): array
    {
        foreach ($this->board->getPieces() as $piece) {
            switch ($piece->getId()) {
                case Symbol::K:
                    $this->result[$piece->getColor()] = array_merge(
                        $this->result[$piece->getColor()],
                        array_values(
                            array_intersect(
                                array_values((array) $piece->getTravel()),
                                $this->sqEval[SqEvaluation::TYPE_USED][$piece->getOppColor()]
                            )
                        )
                    );
                    break;
                case Symbol::P:
                    $this->result[$piece->getColor()] = array_merge(
                        $this->result[$piece->getColor()],
                        array_intersect(
                            $piece->getCaptureSquares(),
                            $this->sqEval[SqEvaluation::TYPE_USED][$piece->getOppColor()]
                        )
                    );
                    break;
                default:
                    $this->result[$piece->getColor()] = array_merge(
                        $this->result[$piece->getColor()],
                        array_intersect(
                            $piece->getSqs(),
                            $this->sqEval[SqEvaluation::TYPE_USED][$piece->getOppColor()]
                        )
                    );
                    break;
            }
        }

        sort($this->result[Symbol::WHITE]);
        sort($this->result[Symbol::BLACK]);

        return $this->result;
    }
}
