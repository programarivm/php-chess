<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\SquareEvaluation;
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
    private $sqEvald;

    /**
     * @param \Chess\Board $board
     */
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

    /**
     * Returns the squares being threatened at the present moment.
     *
     * @return array
     */
    public function evaluate(): array
    {
        foreach ($this->board->getPieces() as $piece) {
            switch ($piece->getIdentity()) {
                case Symbol::KING:
                    $this->result[$piece->getColor()] = array_merge(
                        $this->result[$piece->getColor()],
                        array_values(
                            array_intersect(
                                array_values((array) $piece->getScope()),
                                $this->sqEvald[SquareEvaluation::FEATURE_USED][$piece->getOppColor()]
                            )
                        )
                    );
                    break;
                case Symbol::PAWN:
                    $this->result[$piece->getColor()] = array_merge(
                        $this->result[$piece->getColor()],
                        array_intersect(
                            $piece->getCaptureSquares(),
                            $this->sqEvald[SquareEvaluation::FEATURE_USED][$piece->getOppColor()]
                        )
                    );
                    break;
                default:
                    $this->result[$piece->getColor()] = array_merge(
                        $this->result[$piece->getColor()],
                        array_intersect(
                            $piece->getLegalMoves(),
                            $this->sqEvald[SquareEvaluation::FEATURE_USED][$piece->getOppColor()]
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
