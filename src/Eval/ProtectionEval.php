<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Protection evaluation.
 *
 * Total piece value obtained from the squares that are not being defended.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class ProtectionEval extends AbstractEval implements DiscreteEvalInterface
{
    const NAME = 'Protection';

    /**
     * Phrase.
     *
     * @var array
     */
    protected array $phrase = [
        Color::W => [
            [
                'diff' => 4,
                'meaning' => "White has a decisive protection advantage.",
            ],
            [
                'diff' => 3,
                'meaning' => "White has a significant protection advantage.",
            ],
            [
                'diff' => 2,
                'meaning' => "White has some protection advantage.",
            ],
            [
                'diff' => 1,
                'meaning' => "White has a tiny protection advantage.",
            ],
        ],
        Color::B => [
            [
                'diff' => -4,
                'meaning' => "Black has a decisive protection advantage.",
            ],
            [
                'diff' => -3,
                'meaning' => "Black has a significant protection advantage.",
            ],
            [
                'diff' => -2,
                'meaning' => "Black has some protection advantage.",
            ],
            [
                'diff' => -1,
                'meaning' => "Black has a tiny protection advantage.",
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

        foreach ($this->board->getPieces() as $piece) {
            foreach ($piece->attackedPieces() as $attackedPiece) {
                if ($attackedPiece->getId() !== Piece::K) {
                    if (empty($attackedPiece->defendingPieces())) {
                        $this->result[$attackedPiece->oppColor()] += self::$value[$attackedPiece->getId()];
                        $this->elaborate($attackedPiece);
                    }
                }
            }
        }

        $this->explain($this->result);
    }

    /**
     * Explain the result.
     *
     * @param array $result
     */
    private function explain(array $result): void
    {
        if ($sentence = $this->sentence($result)) {
            $this->phrases[] = $sentence;
        }
    }

    /**
     * Elaborate on the result.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     */
    private function elaborate(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);
        $phrase = ucfirst("$phrase is unprotected.");
        if (!in_array($phrase, $this->phrases)) {
            $this->elaboration[] = $phrase;
        }
    }
}
