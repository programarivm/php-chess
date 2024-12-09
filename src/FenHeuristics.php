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

        foreach ($this->function->dependencies as $val) {
            $this->dependencies[$val] = new $val($this->board);
        }

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
    }

    public function resolve(string $key, ?string $val): AbstractEval
    {
        if ($val) {
            return new $key($this->board, $this->dependencies[$val]);
        } elseif (isset($this->dependencies[$key])) {
            return $this->dependencies[$key];
        }

        return new $key($this->board);
    }
}
