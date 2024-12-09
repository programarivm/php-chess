<?php

namespace Chess\Tutor;

use Chess\FenHeuristics;
use Chess\Eval\ExplainEvalTrait;
use Chess\Eval\ElaborateEvalTrait;
use Chess\Function\AbstractFunction;
use Chess\ML\SumLabeller;
use Chess\Variant\AbstractBoard;

class FenEvaluation extends AbstractParagraph
{
    public function __construct(AbstractFunction $function, AbstractBoard $board)
    {
        $this->function = $function;
        $this->board = $board;

        $this->dependsOn = $this->function->dependencies($this->board);

        $this->paragraph = [
            ...$this->fenExplanation(),
            ...$this->fenElaboration(),
            ...$this->evaluate(),
        ];
    }

    private function fenExplanation(): array
    {
        $paragraph = [];

        foreach ($this->function->getEval() as $key => $val) {
            if ($val) {
                $eval  = new $key($this->board, $this->dependsOn[$val]);
            } elseif (isset($this->dependsOn[$val])) {
                $eval = $this->function->dependsOn[$val];
            } else {
                $eval = new $key($this->board);
            }
            if (in_array(ExplainEvalTrait::class, class_uses($eval))) {
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
        
        foreach ($this->function->getEval() as $key => $val) {
            if ($val) {
                $eval  = new $key($this->board, $this->dependsOn[$val]);
            } elseif (isset($this->dependsOn[$val])) {
                $eval = $this->function->dependsOn[$val];
            } else {
                $eval = new $key($this->board);
            }
            if (in_array(ElaborateEvalTrait::class, class_uses($eval))) {
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
        $sum = (new SumLabeller())->label($balance);
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
