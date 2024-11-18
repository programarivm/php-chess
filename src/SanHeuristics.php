<?php

namespace Chess;

use Chess\Function\AbstractFunction;
use Chess\Play\SanPlay;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

class SanHeuristics extends SanPlay
{
    use SanHeuristicTrait;

    public function __construct(AbstractFunction $function, string $movetext = '', Board $board = null)
    {
        parent::__construct($movetext, $board);

        $this->function = $function;

        $this->calc()->balance()->normalize(-1, 1);
    }

    public function getBalance(): array
    {
        return $this->balance;
    }

    protected function calc(): SanHeuristics
    {
        $this->result = [];

        foreach ($this->function->names() as $i => $name) {
            $this->result[$i][] = $this->item(EvalFactory::create($this->function, $name, $this->board));
        }

        foreach ($this->sanMovetext->moves as $move) {
            if ($move !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $move)) {
                    foreach ($this->function->names() as $i => $name) {
                        $this->result[$i][] = $this->item(EvalFactory::create($this->function, $name, $this->board));
                    }
                }
            }
        }

        return $this;
    }

    protected function balance(): SanHeuristics
    {
        foreach ($this->function->names() as $i => $name) {
            foreach ($this->result[$i] as $j => $result) {
                $this->balance[$i][$j] = round($result[Color::W] - $result[Color::B], 2);
            }
        }

        return $this;
    }

    protected function normalize(int $newMin, int $newMax): SanHeuristics
    {
        $normd = [];
        foreach ($this->balance as $i => $balance) {
            $min = min($balance);
            $max = max($balance);
            foreach ($balance as $j => $val) {
                if ($val > 0) {
                    $normd[$i][$j] = round($this->balance[$i][$j] * $newMax / $max, 2);
                } elseif ($val < 0) {
                    $normd[$i][$j] = round($this->balance[$i][$j] * $newMin / $min, 2);
                } else {
                    $normd[$i][$j] = 0;
                }
            }
        }

        $this->balance = $normd;

        return $this;
    }
}
