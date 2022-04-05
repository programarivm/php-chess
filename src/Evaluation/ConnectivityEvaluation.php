<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\SquareEvaluation;
use Chess\PGN\Symbol;

/**
 * Connectivity.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class ConnectivityEvaluation extends AbstractEvaluation
{
    const NAME = 'connectivity';

    private $sqEvald;

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $sqEval = new SquareEvaluation($board);

        $this->sqEvald = [
            SquareEvaluation::TYPE_FREE => $sqEval->evaluate(SquareEvaluation::TYPE_FREE),
            SquareEvaluation::TYPE_USED => $sqEval->evaluate(SquareEvaluation::TYPE_USED),
        ];

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function evaluate(): array
    {
        $this->color(Symbol::WHITE);
        $this->color(Symbol::BLACK);

        return $this->result;
    }

    private function color(string $color)
    {
        foreach ($this->board->getPiecesByColor($color) as $piece) {
            switch ($piece->getId()) {
                case Symbol::KING:
                    $this->result[$color] += count(
                        array_intersect(array_values((array)$piece->getTravel()),
                        $this->sqEvald[SquareEvaluation::TYPE_USED][$color])
                    );
                    break;
                case Symbol::KNIGHT:
                    $this->result[$color] += count(
                        array_intersect($piece->getTravel(),
                        $this->sqEvald[SquareEvaluation::TYPE_USED][$color])
                    );
                    break;
                case Symbol::PAWN:
                    $this->result[$color] += count(
                        array_intersect($piece->getCaptureSquares(),
                        $this->sqEvald[SquareEvaluation::TYPE_USED][$color])
                    );
                    break;
                default:
                    foreach ((array)$piece->getTravel() as $key => $val) {
                        foreach ($val as $sq) {
                            if (in_array($sq, $this->sqEvald[SquareEvaluation::TYPE_USED][$color])) {
                                $this->result[$color] += 1;
                                break;
                            } elseif (in_array($sq, $this->sqEvald[SquareEvaluation::TYPE_USED][$piece->getOppColor()])) {
                                break;
                            }
                        }
                    }
                    break;
            }
        }
    }
}
