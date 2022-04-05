<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\SqEvaluation;
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
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function eval(): array
    {
        $this->color(Symbol::WHITE);
        $this->color(Symbol::BLACK);

        return $this->result;
    }

    private function color(string $color)
    {
        foreach ($this->board->getPiecesByColor($color) as $piece) {
            switch ($piece->getId()) {
                case Symbol::K:
                    $this->result[$color] += count(
                        array_intersect(array_values((array)$piece->getTravel()),
                        $this->sqEval[SqEvaluation::TYPE_USED][$color])
                    );
                    break;
                case Symbol::N:
                    $this->result[$color] += count(
                        array_intersect($piece->getTravel(),
                        $this->sqEval[SqEvaluation::TYPE_USED][$color])
                    );
                    break;
                case Symbol::P:
                    $this->result[$color] += count(
                        array_intersect($piece->getCaptureSquares(),
                        $this->sqEval[SqEvaluation::TYPE_USED][$color])
                    );
                    break;
                default:
                    foreach ((array)$piece->getTravel() as $key => $val) {
                        foreach ($val as $sq) {
                            if (in_array($sq, $this->sqEval[SqEvaluation::TYPE_USED][$color])) {
                                $this->result[$color] += 1;
                                break;
                            } elseif (in_array($sq, $this->sqEval[SqEvaluation::TYPE_USED][$piece->getOppColor()])) {
                                break;
                            }
                        }
                    }
                    break;
            }
        }
    }
}
