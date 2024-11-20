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

        $this->calc()->ternarize();
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

    protected function ternarize(): void
    {
        foreach ($this->result[Color::W] as $key => $val) {
            $diff = $this->result[Color::W][$key] - $this->result[Color::B][$key];
            $this->balance[$key] = $diff > 0 ? 1 : ($diff < 0 ? -1 : 0);
        }
    }
}
