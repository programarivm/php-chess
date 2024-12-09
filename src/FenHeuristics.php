<?php

namespace Chess;

use Chess\Eval\AbstractEval;
use Chess\Eval\InverseEvalInterface;
use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;

class FenHeuristics
{
    public AbstractFunction $function;

    public AbstractBoard $board;

    public array $dependencies = [];

    public array $result = [];

    public array $balance = [];

    public function __construct(AbstractFunction $function, AbstractBoard $board)
    {
        $this->function = $function;
        $this->board = $board;

        foreach ($this->function->dependencies as $key => $val) {
            $this->dependencies[$key] = new $val($this->board);
        }

        $this->calc();
    }

    public function resolve(string $class, ?string $name): AbstractEval
    {
        if ($name) {
            return new $class($this->board, $this->dependencies[$name]);
        } elseif (isset($this->dependencies[$name])) {
            return $this->dependencies[$name];
        }

        return new $class($this->board);
    }

    protected function calc(): FenHeuristics
    {
        foreach ($this->function->eval as $key => $val) {
            $eval = $this->resolve($key, $val);
            $result = $eval->getResult();
            if (is_array($result[Color::W])) {
                if ($eval instanceof InverseEvalInterface) {
                    $item = [
                        Color::W => count($result[Color::B]),
                        Color::B => count($result[Color::W]),
                    ];
                } else {
                    $item = [
                        Color::W => count($result[Color::W]),
                        Color::B => count($result[Color::B]),
                    ];
                }
            } else {
                if ($eval instanceof InverseEvalInterface) {
                    $item = [
                        Color::W => $result[Color::B],
                        Color::B => $result[Color::W],
                    ];
                } else {
                    $item = $result;
                }
            }
            $this->result[] = $item;
            $diff = $item[Color::W] - $item[Color::B];
            $this->balance[] = $diff > 0 ? 1 : ($diff < 0 ? -1 : 0);
        }

        return $this;
    }
}
