<?php

namespace Chess;

use Chess\Eval\AbstractEval;
use Chess\Eval\InverseEvalInterface;
use Chess\Function\AbstractFunction;
use Chess\Play\SanPlay;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

class SanHeuristics extends SanPlay
{
    protected AbstractFunction $function;

    protected string $name;

    protected array $result = [];

    public array $balance = [];

    public function __construct(
        AbstractFunction $function,
        string $movetext = '',
        string $name = '',
        AbstractBoard $board = null
    ) {
        parent::__construct($movetext, $board);

        $this->function = $function;
        $this->name = $name;

        $this->calc()->balance()->normalize(-1, 1);
    }

    protected function calc(): SanHeuristics
    {
        $this->result[] = $this->item(EvalFactory::create(
            $this->function,
            $this->name,
            $this->board
        ));

        foreach ($this->sanMovetext->moves as $move) {
            if ($move !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $move)) {
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

    protected function balance(): SanHeuristics
    {
        foreach ($this->result as $result) {
            $this->balance[] = $result[Color::W] - $result[Color::B];
        }

        return $this;
    }

    protected function normalize(int $newMin, int $newMax): void
    {
        $min = min($this->balance);
        $max = max($this->balance);

        foreach ($this->balance as $key => $val) {
            if ($val > 0) {
                $this->balance[$key] = round($this->balance[$key] * $newMax / $max, 2);
            } elseif ($val < 0) {
                $this->balance[$key] = round($this->balance[$key] * $newMin / $min, 2);
            } else {
                $this->balance[$key] = 0;
            }
        }
    }

    protected function item(AbstractEval $eval): array
    {
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

        return $item;
    }
}
