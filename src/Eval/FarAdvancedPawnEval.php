<?php

namespace Chess\Eval;

use Chess\Piece\P;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * FarAdvancedPawnEval
 *
 * A pawn is far advanced if it is threatening to promote.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class FarAdvancedPawnEval extends AbstractEval
{
    const NAME = 'Far-advanced pawn';

    /**
     * Phrase.
     *
     * @var array
     */
    protected array $phrase = [
        Color::W => [
            [
                'diff' => 4,
                'meaning' => "White has a decisive far advanced pawn advantage.",
            ],
            [
                'diff' => 3,
                'meaning' => "White has a significant far advanced pawn advantage.",
            ],
            [
                'diff' => 2,
                'meaning' => "White has some far advanced pawn advantage.",
            ],
            [
                'diff' => 1,
                'meaning' => "White has a tiny far advanced pawn advantage.",
            ],
        ],
        Color::B => [
            [
                'diff' => -4,
                'meaning' => "Black has a decisive far advanced pawn advantage.",
            ],
            [
                'diff' => -3,
                'meaning' => "Black has a significant far advanced pawn advantage.",
            ],
            [
                'diff' => -2,
                'meaning' => "Black has some far advanced pawn advantage.",
            ],
            [
                'diff' => -1,
                'meaning' => "Black has a tiny far advanced pawn advantage.",
            ],
        ],
    ];

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::P && $this->isFarAdvancedPawn($piece)) {
                $this->result[$piece->getColor()][] = $piece->getSq();
            }
        }

        $this->explain($this->result);

        $this->elaborate($this->result);
    }

    /**
     * Finds out if a pawn is far advanced.
     *
     * @param \Chess\Piece\P $pawn
     * @return bool
     */
    private function isFarAdvancedPawn(P $pawn): bool
    {
        if ($pawn->getColor() === Color::W) {
            if ($pawn->getSqRank() >= 6) {
                return true;
            }
        } else {
            if ($pawn->getSqRank() <= 3) {
                return true;
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
        $singular = $plural = 'threatening to promote';

        $this->shorten($result, $singular, $plural);
    }
}
