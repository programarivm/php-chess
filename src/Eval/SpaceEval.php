<?php

namespace Chess\Eval;

use Chess\Eval\SqEval;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Board;

/**
 * Space evaluation.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class SpaceEval extends AbstractEval
{
    const NAME = 'Space';

    private object $sqEval;

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->sqEval = (new SqEval($board))->eval();

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];
    }

    public function eval(): array
    {
        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        $this->board->rewind();
        while ($this->board->valid()) {
            $piece = $this->board->current();
            switch ($piece->getId()) {
                case Piece::K:
                    $this->result[$piece->getColor()] = array_unique(
                        [
                            ...$this->result[$piece->getColor()],
                            ...array_intersect(
                                (array) $piece->getMobility(),
                                $this->sqEval->free
                            )
                        ]
                    );
                    break;
                case Piece::P:
                    $this->result[$piece->getColor()] = array_unique(
                        [
                            ...$this->result[$piece->getColor()],
                            ...array_intersect(
                                $piece->getCaptureSqs(),
                                $this->sqEval->free
                            )
                        ]
                    );
                    break;
                default:
                    $this->result[$piece->getColor()] = array_unique(
                        [
                            ...$this->result[$piece->getColor()],
                            ...array_diff(
                                $piece->sqs(),
                                $this->sqEval->used->{$piece->oppColor()}
                            )
                        ]
                    );
                    break;
            }
            $this->board->next();
        }

        sort($this->result[Color::W]);
        sort($this->result[Color::B]);

        return $this->result;
    }
}
