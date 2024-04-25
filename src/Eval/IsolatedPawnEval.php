<?php

namespace Chess\Eval;

use Chess\Piece\P;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class IsolatedPawnEval extends AbstractEval implements DiscreteEvalInterface, InverseEvalInterface
{
    const NAME = 'Isolated pawn';

    /**
     * Phrase.
     *
     * @var array
     */
    protected array $phrase = [
        Color::W => [
            [
                'diff' => 4,
                'meaning' => "Black has a decisive isolated pawn advantage.",
            ],
            [
                'diff' => 3,
                'meaning' => "Black has a significant isolated pawn advantage.",
            ],
            [
                'diff' => 2,
                'meaning' => "Black has some isolated pawn advantage.",
            ],
            [
                'diff' => 1,
                'meaning' => "Black has a tiny isolated pawn advantage.",
            ],
        ],
        Color::B => [
            [
                'diff' => -4,
                'meaning' => "White has a decisive isolated pawn advantage.",
            ],
            [
                'diff' => -3,
                'meaning' => "White has a significant isolated pawn advantage.",
            ],
            [
                'diff' => -2,
                'meaning' => "White has some isolated pawn advantage.",
            ],
            [
                'diff' => -1,
                'meaning' => "White has a tiny isolated pawn advantage.",
            ],
        ],
    ];

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::P) {
                if ($this->isIsolatedPawn($piece)) {
                    $this->result[$piece->getColor()][] = $piece->getSq();
                }
            }
        }

        $this->explain($this->result);

        $this->elaborate($this->result);
    }

    private function isIsolatedPawn(P $pawn): int
    {
        $left = chr(ord($pawn->getSq()) - 1);
        $right = chr(ord($pawn->getSq()) + 1);
        for ($i = 2; $i < $this->board->getSize()['ranks']; $i++) {
            if ($piece = $this->board->getPieceBySq($left.$i)) {
                if ($piece->getId() === Piece::P && $piece->getColor() === $pawn->getColor()) {
                    return false;
                }
            }
            if ($piece = $this->board->getPieceBySq($right.$i)) {
                if ($piece->getId() === Piece::P && $piece->getColor() === $pawn->getColor()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Explain the result.
     *
     * @param array $result
     */
    private function explain(array $result): void
    {
        $result[Color::W] = count($result[Color::W]);
        $result[Color::B] = count($result[Color::B]);

        if ($sentence = $this->sentence($result)) {
            $this->phrases[] = $sentence;
        }
    }

    /**
     * Elaborate on the result.
     *
     * @param array $result
     */
    private function elaborate(array $result): void
    {
        $singular = mb_strtolower('an ' . self::NAME);
        $plural = mb_strtolower(self::NAME.'s');

        $this->shorten($result, $singular, $plural);
    }
}
