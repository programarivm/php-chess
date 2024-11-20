<?php

namespace Chess\Tutor;

use Chess\FenHeuristics;
use Chess\Eval\ElaborateEvalInterface;
use Chess\Eval\ExplainEvalInterface;
use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;

class FenEvaluation extends AbstractParagraph
{
    public function __construct(AbstractFunction $function, AbstractBoard $board)
    {
        $this->function = $function;
        $this->board = $board;
        $this->paragraph = [
            ...$this->fenExplanation(),
            ...$this->fenElaboration(),
            ...$this->evaluate(),
        ];
    }

    private function fenExplanation(): array
    {
        $paragraph = [];

        foreach ($this->function->getEval() as $val) {
            $eval = new $val($this->board);
            if (is_a($eval, ExplainEvalInterface::class)) {
                if ($phrases = $eval->getExplanation()) {
                    $paragraph = [...$paragraph, ...$phrases];
                }
            }
        }

        return $paragraph;
    }

    private function fenElaboration(): array
    {
        $paragraph = [];

        foreach ($this->function->getEval() as $val) {
            $eval = new $val($this->board);
            if (is_a($eval, ElaborateEvalInterface::class)) {
                if ($phrases = $eval->getElaboration()) {
                    $paragraph = [...$paragraph, ...$phrases];
                }
            }
        }

        return $paragraph;
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
