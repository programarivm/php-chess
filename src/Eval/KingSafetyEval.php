<?php

namespace Chess\Eval;

use Chess\Eval\PressureEval;
use Chess\Eval\SpaceEval;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * K safety.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class KingSafetyEval extends AbstractEval implements InverseEvalInterface
{
    const NAME = 'King safety';

    protected array $phrase = [
        Color::W => [
            [
                'diff' => 4,
                'meaning' => "The white king is in dire straits.",
            ],
            [
                'diff' => 3,
                'meaning' => "The black pieces are desperately close to the adversary's king.",
            ],
            [
                'diff' => 2,
                'meaning' => "The black pieces are getting worryingly close to the opponent's king.",
            ],
            [
                'diff' => 1,
                'meaning' => "The black pieces are timidly approaching the other side's king.",
            ],
        ],
        Color::B => [
            [
                'diff' => -4,
                'meaning' => "The black king is in dire straits.",
            ],
            [
                'diff' => -3,
                'meaning' => "The white pieces are desperately close to the adversary's king.",
            ],
            [
                'diff' => -2,
                'meaning' => "The white pieces are getting worryingly close to the opponent's king.",
            ],
            [
                'diff' => -1,
                'meaning' => "The white pieces are timidly approaching the other side's king.",
            ],
        ],
    ];

    public function __construct(Board $board)
    {
        $this->board = $board;

        $pressEval = (new PressureEval($this->board))->getResult();
        $spEval = (new SpaceEval($this->board))->getResult();

        $this->color(Color::W, $pressEval, $spEval);
        $this->color(Color::B, $pressEval, $spEval);

        $this->explain($this->result);
    }

    private function color(string $color, array $pressEval, array $spEval): void
    {
        $king = $this->board->getPiece($color, Piece::K);
        foreach ($king->getMobility() as $key => $sq) {
            if ($piece = $this->board->getPieceBySq($sq)) {
                if ($piece->getColor() === $king->oppColor()) {
                    $this->result[$color] += 1;
                }
            }
            if (in_array($sq, $pressEval[$king->oppColor()])) {
                $this->result[$color] += 1;
            }
            if (in_array($sq, $spEval[$king->oppColor()])) {
                $this->result[$color] += 1;
            }
        }
    }

    private function explain(array $result): void
    {
        if ($sentence = $this->sentence($result)) {
            $this->phrases[] = $sentence;
        }
    }
}
