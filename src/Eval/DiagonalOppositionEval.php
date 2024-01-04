<?php

namespace Chess\Eval;

use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class DiagonalOppositionEval extends AbstractEval implements TernaryEvalInterface
{
    const NAME = 'Diagonal opposition';

    protected array $phrase = [
        Color::W => [
            [
                'diff' => 1,
                'meaning' => "The white king has the diagonal opposition preventing the advance of the other king.",
            ],
        ],
        Color::B => [
            [
                'diff' => -1,
                'meaning' => "The black king has the diagonal opposition preventing the advance of the other king.",
            ],
        ],
    ];

    public function __construct(Board $board)
    {
        $this->board = $board;

        $wKingMobility = $this->board->getPiece(Color::W, Piece::K)->getMobility();
        $bKingMobility = $this->board->getPiece(Color::B, Piece::K)->getMobility();

        $wKingMobilityArr = array_values((array) $wKingMobility);
        $bKingMobilityArr = array_values((array) $bKingMobility);

        $intersect = array_intersect($wKingMobilityArr, $bKingMobilityArr);

        if (count($intersect) === 1) {
            $this->result = [
                Color::W => (int) ($this->board->getTurn() !== Color::W),
                Color::B => (int) ($this->board->getTurn() !== Color::B),
            ];
        }

        $this->explain($this->result);
    }

    private function explain(array $result): void
    {
        if ($sentence = $this->sentence($result)) {
            $this->phrases[] = $sentence;
        }
    }
}
