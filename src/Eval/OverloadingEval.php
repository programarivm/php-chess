<?php

namespace Chess\Eval;

use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Overloading Evaluation
 *
 * A piece that is overloaded with defensive tasks is vulnerable because it can
 * be deflected, meaning it could be forced to leave the square it occupies,
 * typically resulting in an advantage for the opponent.
 */
class OverloadingEval extends AbstractEval implements InverseEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Overloading';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        $this->range = [1, 4];

        $this->subject = [
            'Black',
            'White',
        ];

        $this->observation = [
            "has a slight overloading advantage",
            "has a moderate overloading advantage",
            "has a total overloading advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id !== Piece::K) {
                $defended = $piece->defended();
                $countDefended = count($defended);
                $countAttacking = 0;
                if ($countDefended > 1) {
                    foreach ($defended as $val) {
                        if (count($val->attacking()) >= count($val->defending())) {
                            $countAttacking += 1;
                        }
                    }
                    if ($countAttacking >= 2) {
                        $this->result[$piece->color][] = $piece->sq;
                        $this->toElaborate[] = $piece;
                    }
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
            $this->elaboration[] = ucfirst(PiecePhrase::create($val)) . 
                " is overloaded with defensive tasks.";
        }

        return $this->elaboration;
    }
}
