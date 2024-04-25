<?php

namespace Chess\Eval;

use Chess\Eval\IsolatedPawnEval;
use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class BackwardPawnEval extends AbstractEval implements DiscreteEvalInterface, InverseEvalInterface
{
    const NAME = 'Backward pawn';

    /**
     * Phrase.
     *
     * @var array
     */
    protected array $phrase = [
        Color::W => [
            [
                'diff' => 4,
                'meaning' => "Black has a decisive backward pawn advantage.",
            ],
            [
                'diff' => 3,
                'meaning' => "Black has a significant backward pawn advantage.",
            ],
            [
                'diff' => 2,
                'meaning' => "Black has some backward pawn advantage.",
            ],
            [
                'diff' => 1,
                'meaning' => "Black has a tiny backward pawn advantage.",
            ],
        ],
        Color::B => [
            [
                'diff' => -4,
                'meaning' => "White has a decisive backward pawn advantage.",
            ],
            [
                'diff' => -3,
                'meaning' => "White has a significant backward pawn advantage.",
            ],
            [
                'diff' => -2,
                'meaning' => "White has some backward pawn advantage.",
            ],
            [
                'diff' => -1,
                'meaning' => "White has a tiny backward pawn advantage.",
            ],
        ],
    ];

    private array $isolatedPawnEval;

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        $this->isolatedPawnEval = (new IsolatedPawnEval($board))->getResult();

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::P) {
                $left = chr(ord($piece->getSq()) - 1);
                $right = chr(ord($piece->getSq()) + 1);
                if (
                    !$this->isDefensible($piece, $left) &&
                    !$this->isDefensible($piece, $right) &&
                    !in_array($piece->getSq(), [
                        ...$this->isolatedPawnEval[Color::W],
                        ...$this->isolatedPawnEval[Color::B]
                    ])
                ) {
                    $this->result[$piece->getColor()][] = $piece->getSq();
                }
            }
        }

        $this->explain($this->result);

        $this->elaborate($this->result);
    }

    private function isDefensible(AbstractPiece $pawn, string $file): bool
    {
        if ($pawn->getSqRank() == 2 || $pawn->getSqRank() == $this->board->getSize()['ranks'] - 1) {
            return true;
        }

        if ($pawn->getColor() === Color::W) {
            for ($i = $pawn->getSqRank() - 1; $i >= 2; $i--) {
                if ($piece = $this->board->getPieceBySq($file.$i)) {
                    if ($piece->getId() === Piece::P && $piece->getColor() === $pawn->getColor()) {
                        return true;
                    }
                }
            }
        } else {
            for ($i = $pawn->getSqRank() + 1; $i <= $this->board->getSize()['ranks'] - 1; $i++) {
                if ($piece = $this->board->getPieceBySq($file.$i)) {
                    if ($piece->getId() === Piece::P && $piece->getColor() === $pawn->getColor()
                    ) {
                        return true;
                    }
                }
            }
        }

        return false;
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
        $singular = mb_strtolower('a ' . self::NAME);
        $plural = mb_strtolower(self::NAME.'s');

        $this->shorten($result, $singular, $plural);
    }
}
