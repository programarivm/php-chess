<?php

namespace Chess\Eval;

use Chess\Eval\IsolatedPawnEval;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\P;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Backward Pawn Evaluation
 *
 * The last pawn protecting other pawns in its chain. It is considered a
 * weakness because it cannot advance safely.
 */
class BackwardPawnEval extends AbstractEval implements InverseEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Backward pawn';

    /**
     * Isolated pawn evaluation.
     *
     * @var array
     */
    private array $isolatedPawnEval;

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

        $this->range = [1];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has more backward pawns against",
        ];

        $this->isolatedPawnEval = (new IsolatedPawnEval($board))->result;

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::P) {
                $left = chr(ord($piece->sq) - 1);
                $right = chr(ord($piece->sq) + 1);
                if (
                    !$this->isDefensible($piece, $left) &&
                    !$this->isDefensible($piece, $right) &&
                    !in_array($piece->sq, [
                        ...$this->isolatedPawnEval[Color::W],
                        ...$this->isolatedPawnEval[Color::B]
                    ])
                ) {
                    $this->result[$piece->color][] = $piece->sq;
                    $this->toElaborate[] = $piece;
                }
            }
        }
    }

    /**
     * Returns true if the given pawn can be defended by other pawns in its chain.
     *
     * @param \namespace Chess\Tests\Unit\Variant\Classical; $pawn
     * @param string $file
     * @return bool
     */
    private function isDefensible(P $pawn, string $file): bool
    {
        if ($pawn->rank() == 2 || $pawn->rank() == $this->board->square::SIZE['ranks'] - 1) {
            return true;
        }

        if ($pawn->color === Color::W) {
            for ($i = $pawn->rank() - 1; $i >= 2; $i--) {
                if ($piece = $this->board->pieceBySq($file . $i)) {
                    if ($piece->id === Piece::P && $piece->color === $pawn->color) {
                        return true;
                    }
                }
            }
        } else {
            for ($i = $pawn->rank() + 1; $i <= $this->board->square::SIZE['ranks'] - 1; $i++) {
                if ($piece = $this->board->pieceBySq($file . $i)) {
                    if (
                        $piece->id === Piece::P && $piece->color === $pawn->color
                    ) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Elaborate on the evaluation.
     *
     * @return array
     */
    public function elaborate(): array
    {
        foreach ($this->toElaborate as $val) {
            $this->elaboration[] = $val->sq;
        }

        $this->shorten('These pawns are bakward: ', $ucfirst = false);

        return $this->elaboration;
    }
}
