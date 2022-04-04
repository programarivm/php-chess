<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\SquareEvaluation;
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

    private $sqEvald;

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $sqEval = new SquareEvaluation($board);

        $this->sqEvald = [
            SquareEvaluation::FEATURE_FREE => $sqEval->evaluate(SquareEvaluation::FEATURE_FREE),
            SquareEvaluation::FEATURE_USED => $sqEval->evaluate(SquareEvaluation::FEATURE_USED),
        ];

        $this->result = [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];
    }

    public function evaluate(): array
    {
        $this->result = [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];

        $this->board->rewind();
        while ($this->board->valid()) {
            $piece = $this->board->current();
            switch ($piece->getId()) {
                case Symbol::KING:
                    $this->result[$piece->getColor()] = array_unique(
                        array_merge(
                            $this->result[$piece->getColor()],
                            array_values(
                                array_intersect(
                                    array_values((array) $piece->getScope()),
                                    $this->sqEvald[SquareEvaluation::FEATURE_FREE]
                                )
                            )
                        )
                    );
                    break;
                case Symbol::PAWN:
                    $this->result[$piece->getColor()] = array_unique(
                        array_merge(
                            $this->result[$piece->getColor()],
                            array_intersect(
                                $piece->getCaptureSquares(),
                                $this->sqEvald[SquareEvaluation::FEATURE_FREE]
                            )
                        )
                    );
                    break;
                default:
                    $this->result[$piece->getColor()] = array_unique(
                        array_merge(
                            $this->result[$piece->getColor()],
                            array_diff(
                                $piece->getSquares(),
                                $this->sqEvald[SquareEvaluation::FEATURE_USED][$piece->getOppColor()]
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
