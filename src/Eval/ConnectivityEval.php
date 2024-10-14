<?php

namespace Chess\Eval;

use Chess\Eval\SqCount;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Piece;

class ConnectivityEval extends AbstractEval implements ExplainEvalInterface
{
    use ExplainEvalTrait;

    const NAME = 'Connectivity';

    private array $sqCount;

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->sqCount = (new SqCount($board))->count();

        $this->range = [1, 4];

        $this->subject =  [
            'The white pieces',
            'The black pieces',
        ];

        $this->observation = [
            "are slightly better connected",
            "are significantly better connected",
            "are totally better connected",
        ];

        foreach ($this->board->pieces() as $piece) {
            switch ($piece->id) {
                case Piece::K:
                    $this->result[$piece->color] += count(
                        array_intersect(
                            $piece->mobility,
                            $this->sqCount['used'][$piece->color]
                        )
                    );
                    break;
                case Piece::N:
                    $this->result[$piece->color] += count(
                        array_intersect(
                            $piece->mobility,
                            $this->sqCount['used'][$piece->color]
                        )
                    );
                    break;
                case Piece::P:
                    $this->result[$piece->color] += count(
                        array_intersect(
                            $piece->captureSqs,
                            $this->sqCount['used'][$piece->color]
                        )
                    );
                    break;
                default:
                    foreach ($piece->mobility as $key => $val) {
                        foreach ($val as $sq) {
                            if (in_array($sq, $this->sqCount['used'][$piece->color])) {
                                $this->result[$piece->color] += 1;
                                break;
                            } elseif (in_array($sq, $this->sqCount['used'][$piece->oppColor()])) {
                                break;
                            }
                        }
                    }
                    break;
            }
        }

        $this->explain($this->result);
    }
}
