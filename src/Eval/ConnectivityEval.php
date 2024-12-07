<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

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
                    $this->elaborate($piece);
                }
            }
        }

        $this->shorten('These pieces are hanging: ', $ucfirst = true);

        $this->explain($this->result);
    }

    /**
     * Elaborate on the evaluation.
     *
     * @param \Chess\Variant\AbstractPiece $piece
     */
    private function elaborate(AbstractPiece $piece): void
    {
        $this->elaboration[] = PiecePhrase::create($piece);
    }
}
