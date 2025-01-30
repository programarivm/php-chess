<?php

namespace Chess\Eval;

use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Attack Evaluation
 *
 * If a piece is under threat of being attacked, it means it could be taken
 * after a sequence of captures resulting in a material gain. This indicates a
 * forcing move in that a player is to reply in a certain way. On the next turn,
 * it should be defended by a piece or moved to a safe square. The player with
 * the greater number of material points under attack has an advantage.
 *
 * @see \Chess\Eval\ProtectionEval
 */
class AttackEval extends AbstractEval
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Attack';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [0.8, 5];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight attack advantage",
            "has a moderate attack advantage",
            "has a total attack advantage",
        ];

        if (
            !$this->board->isCheck() &&
            !$this->board->isMate() &&
            !$this->board->isStalemate()
        ) {
            foreach ($this->board->pieces() as $piece) {
                if ($piece->id !== Piece::K) {
                    $clone = $this->board->clone();
                    $clone->turn = $piece->oppColor();
                    $attack = [
                        Color::W => 0,
                        Color::B => 0,
                    ];
                    foreach ($piece->attacking() as $attacking) {
                        $capturedPiece = $clone->pieceBySq($piece->sq);
                        if ($clone->playLan($clone->turn, $attacking->sq . $piece->sq)) {
                            $attack[$attacking->color] += self::$value[$capturedPiece->id];
                            foreach ($piece->defending() as $defending) {
                                $capturedPiece = $clone->pieceBySq($piece->sq);
                                if ($clone->playLan($clone->turn, $defending->sq . $piece->sq)) {
                                    $attack[$defending->color] += self::$value[$capturedPiece->id];
                                }
                            }
                        }
                    }
                    $diff = $attack[Color::W] - $attack[Color::B];
                    if ($piece->oppColor() === Color::W) {
                        if ($diff > 0) {
                            $this->result[Color::W] += $diff;
                            $this->toElaborate[] = $piece;
                        }
                    } else {
                        if ($diff < 0) {
                            $this->result[Color::B] += abs($diff);
                            $this->toElaborate[] = $piece;
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
            $this->elaboration[] = ucfirst(PiecePhrase::create($val)) . 
                " is under threat of being attacked.";
        }

        return $this->elaboration;
    }
}
