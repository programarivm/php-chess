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

    protected array $result;

    protected array $balance = [];

    public function __construct(AbstractFunction $function, AbstractBoard $board)
    {
        $this->function = $function;
        $this->board = $board;

        $this->calc()->balance()->ternarize();
    }

    public function getBalance(): array
    {
        return $this->balance;
    }

    protected function calc(): FenHeuristics
    {
        foreach ($this->function->getEval() as $val) {
            $eval = new $val($this->board);
            $result = $eval->getResult();
            if (is_array($result[Color::W])) {
                if ($eval instanceof InverseEvalInterface) {
                    $item[] = [
                        Color::W => count($result[Color::B]),
                        Color::B => count($result[Color::W]),
                    ];
                } else {
                    $item[] = [
                        Color::W => count($result[Color::W]),
                        Color::B => count($result[Color::B]),
                    ];
                }
            } else {
                if ($eval instanceof InverseEvalInterface) {
                    $item[] = [
                        Color::W => $result[Color::B],
                        Color::B => $result[Color::W],
                    ];
                } else {
                    $item[] = $result;
                }
            }
        }

        $this->result[Color::W] = array_column($item, Color::W);
        $this->result[Color::B] = array_column($item, Color::B);

        return $this;
    }

    protected function balance(): FenHeuristics
    {
        foreach ($this->result[Color::W] as $key => $val) {
            $this->balance[$key] = $this->result[Color::W][$key] - $this->result[Color::B][$key];
        }

        return $this;
    }

    protected function ternarize(): void
    {
        for ($i = 0; $i < count($this->balance); $i++) {
            $this->balance[$i] = $this->balance[$i] > 0 ? 1 : ($this->balance[$i] < 0 ? -1 : 0);
        }
    }
}
