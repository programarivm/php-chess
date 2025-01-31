<?php

namespace Chess\Eval;

use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractLinePiece;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Relative Pin Evaluation
 *
 * A tactic that occurs when a piece is shielding a more valuable piece, so if
 * it moves out of the line of attack the more valuable piece can be captured
 * resulting in a material gain.
 */
class RelativePinEval extends AbstractEval
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Relative pin';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a relative pin advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            foreach ($piece->attacking() as $attacking) {
                if (is_a($attacking, AbstractLinePiece::class)) {
                    foreach ($this->board->pieces($piece->color) as $val) {
                        if ($val->id !== Piece::K &&
                            $this->board->square->isBetweenSqs($attacking->sq, $piece->sq, $val->sq) &&
                            $this->board->isEmptyLine($this->board->square->line($piece->sq, $val->sq))
                        ) {
                            $diff = self::$value[$attacking->id] - self::$value[$val->id];
                            if ($diff < 0) {
                                $this->result[$piece->oppColor()] += abs(round($diff, 4));
                                $this->toElaborate[] = [
                                    $piece,
                                    $attacking,
                                    $val,
                                ];
                            }
                        }
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
            $this->elaboration[] = ucfirst(PiecePhrase::create($val[0]))  . 
                " is relatively pinned by " . 
                PiecePhrase::create($val[1]) . 
                " shielding " . 
                PiecePhrase::create($val[2]) . 
                ".";
        }

        return $this->elaboration;
    }
}
