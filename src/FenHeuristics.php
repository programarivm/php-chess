<?php

namespace Chess;

use Chess\Eval\InverseEvalInterface;
use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;

class FenHeuristics
{
    protected AbstractFunction $function;

    protected AbstractBoard $board;

    protected array $result = [];

    protected array $balance = [];

    protected array $dependencies = [];

    public function __construct(AbstractFunction $function, AbstractBoard $board)
    {
        $this->function = $function;
        $this->board = $board;

        $this->dependencies()->calc();
    }

    public function getBalance(): array
    {
        return $this->balance;
    }

    protected function dependencies(): FenHeuristics
    {
        foreach ($this->function->dependencies as $key => $val) {
            $this->dependencies[$key] = new $val($this->board);
        }

        return $this;
    }

    protected function calc(): FenHeuristics
    {
        foreach ($this->function->getEval() as $key => $val) {
            if ($val) {
                $eval  = new $key($this->board, $this->dependencies[$val]);
            } elseif (isset($this->dependencies[$val])) {
                $eval = $this->function->dependencies[$val];
            } else {
                $eval = new $key($this->board);
            }
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
