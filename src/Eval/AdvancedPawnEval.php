<?php

namespace Chess\Eval;

use Chess\Piece\P;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * AdvancedPawnEval
 *
 * A pawn is advanced if it is on the opponent's side of the board
 * (the fifth rank or higher).
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class AdvancedPawnEval extends AbstractEval implements DiscreteEvalInterface
{
    const NAME = 'Advanced pawn';

    /**
     * Phrase.
     *
     * @var array
     */
    protected array $phrase = [
        Color::W => [
            [
                'diff' => 4,
                'meaning' => "White has a decisive passed pawn advantage.",
            ],
            [
                'diff' => 3,
                'meaning' => "White has a significant passed pawn advantage.",
            ],
            [
                'diff' => 2,
                'meaning' => "White has some passed pawn advantage.",
            ],
            [
                'diff' => 1,
                'meaning' => "White has a tiny passed pawn advantage.",
            ],
        ],
        Color::B => [
            [
                'diff' => -4,
                'meaning' => "Black has a decisive passed pawn advantage.",
            ],
            [
                'diff' => -3,
                'meaning' => "Black has a significant passed pawn advantage.",
            ],
            [
                'diff' => -2,
                'meaning' => "Black has some passed pawn advantage.",
            ],
            [
                'diff' => -1,
                'meaning' => "Black has a tiny passed pawn advantage.",
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
            if ($piece->getId() === Piece::P && $this->isAdvancedPawn($piece)) {
                $this->result[$piece->getColor()][] = $piece->getSq();
            }
        }

        $this->explain($this->result);

        $this->elaborate($this->result);
    }

    /**
     * Finds out if a pawn is advanced.
     *
     * @param \Chess\Piece\P $pawn
     * @return bool
     */
    private function isAdvancedPawn(P $pawn): bool
    {
        if ($pawn->getColor() === Color::W) {
            if ($pawn->getSqRank() >= 5) {
                return true;
            }
        } else {
            if ($pawn->getSqRank() <= 4) {
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
        $singular = mb_strtolower('an ' . self::NAME);
        $plural = mb_strtolower(self::NAME.'s');

        $this->shorten($result, $singular, $plural);
    }
}
