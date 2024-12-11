<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Square Outpost Evaluation
 *
 * A square protected by a pawn that cannot be attacked by an opponent's pawn.
 */
class SqOutpostEval extends AbstractEval
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait {
        explain as private doExplain;
    }

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Outpost square';

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
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight outpost advantage",
            "has a moderate outpost advantage",
            "has a total outpost advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::P) {
                $captureSqs = $piece->captureSqs;
                if ($piece->ranks['end'] !== (int) substr($captureSqs[0], 1)) {
                    $left = chr(ord($captureSqs[0]) - 1);
                    $right = chr(ord($captureSqs[0]) + 1);
                    if (
                        !$this->isFileAttacked($piece->color, $captureSqs[0], $left) &&
                        !$this->isFileAttacked($piece->color, $captureSqs[0], $right)
                    ) {
                        $this->result[$piece->color][] = $captureSqs[0];
                        $this->toElaborate[] = $captureSqs[0];
                    }
                    if (isset($captureSqs[1])) {
                        $left = chr(ord($captureSqs[1]) - 1);
                        $right = chr(ord($captureSqs[1]) + 1);
                        if (
                            !$this->isFileAttacked($piece->color, $captureSqs[1], $left) &&
                            !$this->isFileAttacked($piece->color, $captureSqs[1], $right)
                        ) {
                            $this->result[$piece->color][] = $captureSqs[1];
                            $this->toElaborate[] = $captureSqs[1];
                        }
                    }
                }
            }
        }

        $this->result[Color::W] = array_flip(array_flip($this->result[Color::W]));
        $this->result[Color::B] = array_flip(array_flip($this->result[Color::B]));
    }

    /**
     * Returns true if the given file can be attacked by an opponent's pawn.
     *
     * @param string $color
     * @param string $sq
     * @param string $file
     * @return bool
     */
    private function isFileAttacked(string $color, string $sq, string $file): bool
    {
        $rank = substr($sq, 1);
        for ($i = 2; $i < 8; $i++) {
            if ($piece = $this->board->pieceBySq($file . $i)) {
                if (
                    $piece->id === Piece::P &&
                    $piece->color === $this->board->color->opp($color)
                ) {
                    if ($color === Color::W) {
                        if ($i > $rank) {
                            return true;
                        }
                    } else {
                        if ($i < $rank) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * Explain the evaluation.
     *
     * @return array
     */
    public function explain(): array
    {
        $this->doExplain([
            Color::W => count($this->result[Color::W]),
            Color::B => count($this->result[Color::B]),
        ]);

        return $this->explanation;
    }

    /**
     * Elaborate on the evaluation.
     *
     * @return array
     */
    public function elaborate(): array
    {
        foreach ($this->toElaborate as $val) {
            $this->elaboration[] = $val;
        }

        $this->shorten('These are outpost squares: ', $ucfirst = false);

        return $this->elaboration;
    }
}
