<?php

namespace Chess\Eval;

use Chess\Eval\SqCount;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class SpaceEval extends AbstractEval implements ExplainEvalInterface
{
    use ExplainEvalTrait;

    const NAME = 'Space';

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        $this->range = [1, 9];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight space advantage",
            "has a moderate space advantage",
            "has a total space advantage",
        ];

        $sqCount = (new SqCount($board))->count();

        foreach ($pieces = $this->board->pieces() as $piece) {
            if ($piece->id === Piece::K) {
                $this->result[$piece->color] = array_unique(
                    [
                        ...$this->result[$piece->color],
                        ...array_intersect(
                            $piece->mobility,
                            $sqCount['free']
                        )
                    ]
                );
            } elseif ($piece->id === Piece::P) {
                $this->result[$piece->color] = array_unique(
                    [
                        ...$this->result[$piece->color],
                        ...array_intersect(
                            $piece->captureSqs,
                            $sqCount['free']
                        )
                    ]
                );
            } else {
                $this->result[$piece->color] = array_unique(
                    [
                        ...$this->result[$piece->color],
                        ...array_diff(
                            $piece->moveSqs(),
                            $sqCount['used'][$piece->oppColor()]
                        )
                    ]
                );
            }
        }

        $this->explain([
            Color::W => count($this->result[Color::W]),
            Color::B => count($this->result[Color::B]),
        ]);
    }
}
