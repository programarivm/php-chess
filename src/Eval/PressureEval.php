<?php

namespace Chess\Eval;

use Chess\Eval\SqEval;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Board;

/**
 * Pressure evaluation.
 *
 * Squares being threatened at the present moment.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class PressureEval extends AbstractEval
{
    const NAME = 'Pressure';

    /**
     * Square evaluation containing the free and used squares.
     *
     * @var object
     */
    private object $sqEval;

    /**
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->sqEval = (new SqEval($board))->eval();

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];
    }

    /**
     * Returns the squares being threatened at the present moment.
     *
     * @return array
     */
    public function eval(): array
    {
        foreach ($this->board->getPieces() as $piece) {
            switch ($piece->getId()) {
                case Piece::K:
                    $this->result[$piece->getColor()] = [
                        ...$this->result[$piece->getColor()],
                        ...array_intersect(
                            (array) $piece->getMobility(),
                            $this->sqEval->used->{$piece->oppColor()}
                        )
                    ];
                    break;
                case Piece::P:
                    $this->result[$piece->getColor()] = [
                        ...$this->result[$piece->getColor()],
                        ...array_intersect(
                            $piece->getCaptureSqs(),
                            $this->sqEval->used->{$piece->oppColor()}
                        )
                    ];
                    break;
                default:
                    $this->result[$piece->getColor()] = [
                        ...$this->result[$piece->getColor()],
                        ...array_intersect(
                            $piece->sqs(),
                            $this->sqEval->used->{$piece->oppColor()}
                        )
                    ];
                    break;
            }
        }

        sort($this->result[Color::W]);
        sort($this->result[Color::B]);

        return $this->result;
    }
}
