<?php

namespace Chess\Eval;

use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Color;

trait ExplainEvalTrait
{
    protected array $range;

    protected array $subject;

    protected array $observation;

    protected array $explanation = [];

    public function getExplanation(): array
    {
        return $this->explanation;
    }

    protected function meaning(array $result): ?string
    {
        $meanings = $this->meanings();

        $diff = $result[Color::W] - $result[Color::B];

        if ($diff > 0) {
            foreach ($meanings[Color::W] as $item) {
                if ($diff >= $item['diff']) {
                    return $item['meaning'];
                }
            }
        }

        if ($diff < 0) {
            foreach ($meanings[Color::B] as $item) {
                if ($diff <= $item['diff']) {
                    return $item['meaning'];
                }
            }
        }

        return null;
    }

    protected function meanings(): array
    {
        $meanings = [
            Color::W => [],
            Color::B => [],
        ];

        $diff = $this->range[0];

        foreach ($this->observation as $key => $val) {
            $meanings[Color::W][] = [
                'diff' => $diff,
                'meaning' => "{$this->subject[0]} {$val}."
            ];
            $meanings[Color::B][] = [
                'diff' => $diff * -1,
                'meaning' => "{$this->subject[1]} {$val}."
            ];
            isset($this->range[1])
                ? $diff += intdiv($this->range[1] - $this->range[0], count($this->observation) - 1)
                : $diff = $this->range[0];
        }

        return [
            Color::W => array_reverse($meanings[Color::W]),
            Color::B => array_reverse($meanings[Color::B]),
        ];
    }

    public function explain(array $result): void
    {
        if ($meaning = $this->meaning($result)) {
            $this->explanation[] = $meaning;
        }
    }
}
