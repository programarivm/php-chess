<?php

namespace Chess\Tutor;

use Chess\Eval\ExplainEvalTrait;
use Chess\Eval\ElaborateEvalTrait;
use Chess\Eval\AbstractFunction;
use Chess\Variant\AbstractBoard;

class FenEvaluation extends AbstractParagraph
{
    public function __construct(AbstractFunction $f, AbstractBoard $board)
    {
        $this->f = $f;
        $this->board = $board;
        $this->paragraph = [
            ...$this->fenExplanation(),
            ...$this->fenElaboration(),
            ...$this->steinitz(),
        ];
    }

    private function fenExplanation(): array
    {
        $paragraph = [];

        foreach ($this->f::$eval as $val) {
            $eval = new $val($this->board);
            if (in_array(ExplainEvalTrait::class, class_uses($eval))) {
                if ($phrases = $eval->explain()) {
                    $paragraph = [...$paragraph, ...$phrases];
                }
            }
        }

        return $paragraph;
    }

    private function fenElaboration(): array
    {
        $paragraph = [];

        foreach ($this->f::$eval as $val) {
            $eval = new $val($this->board);
            if (in_array(ElaborateEvalTrait::class, class_uses($eval))) {
                if ($phrases = $eval->elaborate()) {
                    $paragraph = [...$paragraph, ...$phrases];
                }
            }
        }

        return $paragraph;
    }

    private function steinitz(): array
    {
        $steinitz = $this->f::steinitz($this->board);

        if ($steinitz > 0) {
            $color = 'White';
        } elseif ($steinitz < 0) {
            $color = 'Black';
            $steinitz = abs($steinitz);
        } else {
            $color = 'either player';
        }

        return [
            "Overall, {$steinitz} {$this->noun($steinitz)} {$this->verb($steinitz)} favoring {$color}.",
        ];
    }

    private function noun(int $count): string
    {
        return $count === 1 ? 'evaluation feature' : 'evaluation features';
    }

    private function verb(int $count)
    {
        return $count === 1 ? 'is' : 'are';
    }
}
