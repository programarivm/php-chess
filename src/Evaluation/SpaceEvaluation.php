<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\SqEvaluation;
use Chess\PGN\Symbol;

/**
 * Space evaluation.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class SpaceEvaluation extends AbstractEvaluation
{
    const NAME = 'space';

    private $sqEval;

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

    public function eval(): array
    {
        $this->result = [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];

        $this->board->rewind();
        while ($this->board->valid()) {
            $piece = $this->board->current();
            switch ($piece->getId()) {
                case Symbol::K:
                    $this->result[$piece->getColor()] = array_unique(
                        array_merge(
                            $this->result[$piece->getColor()],
                            array_values(
                                array_intersect(
                                    array_values((array) $piece->getTravel()),
                                    $this->sqEval[SqEvaluation::TYPE_FREE]
                                )
                            )
                        )
                    );
                    break;
                case Symbol::P:
                    $this->result[$piece->getColor()] = array_unique(
                        array_merge(
                            $this->result[$piece->getColor()],
                            array_intersect(
                                $piece->getCaptureSquares(),
                                $this->sqEval[SqEvaluation::TYPE_FREE]
                            )
                        )
                    );
                    break;
                default:
                    $this->result[$piece->getColor()] = array_unique(
                        array_merge(
                            $this->result[$piece->getColor()],
                            array_diff(
                                $piece->getSqs(),
                                $this->sqEval[SqEvaluation::TYPE_USED][$piece->getOppColor()]
                            )
                        )
                    );
                    break;
            }
            $this->board->next();
        }

        sort($this->result[Symbol::WHITE]);
        sort($this->result[Symbol::BLACK]);

        return $this->result;
    }
}
