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

    public array $balance = [];

    public function __construct(AbstractFunction $function, AbstractBoard $board)
    {
        $this->function = $function;
        $this->board = $board;

        $this->calc()->ternarize();
    }

    protected function calc(): FenHeuristics
    {
        foreach ($this->function->getEval() as $val) {
            $eval = new $val($this->board);
            $result = $eval->getResult();
            if (is_array($result[Color::W])) {
                if ($eval instanceof InverseEvalInterface) {
                    $this->result[] = [
                        Color::W => count($result[Color::B]),
                        Color::B => count($result[Color::W]),
                    ];
                } else {
                    $this->result[] = [
                        Color::W => count($result[Color::W]),
                        Color::B => count($result[Color::B]),
                    ];
                }
            } else {
                if ($eval instanceof InverseEvalInterface) {
                    $this->result[] = [
                        Color::W => $result[Color::B],
                        Color::B => $result[Color::W],
                    ];
                } else {
                    $this->result[] = $result;
                }
            }
        }

        return $this;
    }

    protected function ternarize(): void
    {
        foreach ($this->result as $key => $val) {
            $diff = $val[Color::W] - $val[Color::B];
            $this->balance[$key] = $diff > 0 ? 1 : ($diff < 0 ? -1 : 0);
        }
    }
}
