<?php

namespace Chess\Tutor;

use Chess\FenHeuristics;
use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;

class FenEvaluation extends AbstractParagraph
{
    public function __construct(AbstractFunction $function, AbstractBoard $board)
    {
        $this->function = $function;
        $this->board = $board;
        $this->paragraph = [
            ...(new FenExplanation($this->function, $this->board))->paragraph,
            ...(new FenElaboration($this->function, $this->board))->paragraph,
            ...$this->evaluate(),
        ];
    }

    private function evaluate(): array
    {
        $balance = (new FenHeuristics($this->function, $this->board))->getBalance();
        $sum = array_sum($balance);
        $abs = abs($sum);

        if ($sum > 0) {
            $color = 'White';
        } elseif ($sum < 0) {
            $color = 'Black';
        } else {
            $color = 'either player';
        }

        return [
            "Overall, {$abs} {$this->noun($sum)} {$this->verb($sum)} favoring {$color}.",
        ];
    }

    private function noun(int $abs): string
    {
        return $abs === 1 ? 'evaluation feature' : 'evaluation features';
    }

    private function verb(int $abs)
    {
        return $abs === 1 ? 'is' : 'are';
    }
}
