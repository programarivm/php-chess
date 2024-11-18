<?php

namespace Chess;

use Chess\Function\AbstractFunction;
use Chess\Play\SanPlay;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

class SanHeuristic extends SanPlay
{
    use SanHeuristicTrait;

    protected string $name;

    public function __construct(
        AbstractFunction $function,
        string $name,
        string $movetext = '',
        Board $board = null
    ) {
        parent::__construct($movetext, $board);

        $this->function = $function;
        $this->name = $name;

        $this->calc()->balance()->normalize(-1, 1);
    }

    public function getBalance(): array
    {
        return $this->balance;
    }

    protected function calc(): SanHeuristic
    {
        $this->result[] = $this->item(EvalFactory::create(
            $this->function,
            $this->name, $this->board
        ));

        foreach ($this->sanMovetext->moves as $key => $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $val)) {
                    $this->result[] = $this->item(EvalFactory::create(
                        $this->function,
                        $this->name,
                        $this->board
                    ));
                }
            }
        }

        return $this;
    }

    protected function balance(): SanHeuristic
    {
        foreach ($this->result as $key => $val) {
            $this->balance[$key] = round($val[Color::W] - $val[Color::B], 2);
        }

        return $this;
    }

    protected function normalize(int $newMin, int $newMax): SanHeuristic
    {
        $normd = [];
        $min = min($this->balance);
        $max = max($this->balance);
        
        foreach ($this->balance as $key => $val) {
            if ($val > 0) {
                $normd[$key] = round($this->balance[$key] * $newMax / $max, 2);
            } elseif ($val < 0) {
                $normd[$key] = round($this->balance[$key] * $newMin / $min, 2);
            } else {
                $normd[$key] = 0;
            }
        }

        $this->balance = $normd;

        return $this;
    }
}
