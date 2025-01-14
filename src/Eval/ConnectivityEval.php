<?php

namespace Chess\Eval;

use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Connectivity Evaluation
 *
 * The connectivity of the pieces measures how loosely the pieces are.
 */
class ConnectivityEval extends AbstractEval implements InverseEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Connectivity';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1, 4];

        $this->subject =  [
            'The black pieces',
            'The white pieces',
        ];

        $this->observation = [
            "are slightly better connected",
            "are significantly better connected",
            "are totally better connected",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id !== Piece::K) {
                if (!$piece->defending()) {
                    $this->result[$piece->color] += 1;
                    $this->toElaborate[] = $piece;
                }
            }
        }
    }

    /**
     * Elaborate on the evaluation.
     *
     * @return array
     */
    public function elaborate(): array
    {
        foreach ($this->toElaborate as $val) {
            $this->elaboration[] = PiecePhrase::create($val);
        }

        $this->shorten('These pieces are hanging: ', $ucfirst = true);

        return $this->elaboration;
    }
}
