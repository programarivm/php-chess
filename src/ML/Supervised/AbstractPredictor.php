<?php

namespace Chess\ML\Supervised;

use Chess\Board;
use Rubix\ML\PersistentModel;

abstract class AbstractPredictor
{
    protected $board;

    protected $estimator;

    protected $result = [];

    abstract protected function evaluate(Board $clone): array;

    abstract protected function sort(string $color): AbstractPredictor;

    abstract protected function find(): string;

    public function __construct(Board $board, PersistentModel $estimator)
    {
        $this->board = $board;
        $this->estimator = $estimator;
    }

    protected function sort(string $color): AbstractPredictor
    {
        usort($this->result, function ($a, $b) use ($color) {
            if ($color === Symbol::WHITE) {
                $current = (current($b)['label'] <=> current($a)['label']) * 10 +
                    (current($b)['prediction'] <=> current($a)['prediction']);
            } else {
                $current = (current($a)['label'] <=> current($b)['label']) * 10 +
                    (current($a)['prediction'] <=> current($b)['prediction']);
            }
            return $current;
        });

        return $this;
    }

    public function predict(): string
    {
        $color = $this->board->getTurn();
        foreach ($this->board->getMoves() as $possibleMove) {
            $clone = unserialize(serialize($this->board));
            $clone->play($color, $possibleMove);
            $this->result[] = [ $possibleMove => $this->evaluate($clone) ];
        }
        $found = $this->sort($color)->find();

        return $found;
    }
}
