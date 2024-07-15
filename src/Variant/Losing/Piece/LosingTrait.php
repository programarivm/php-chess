<?php

namespace Chess\Variant\Losing\Piece;

trait LosingTrait
{
    public function moveSqs(): array
    {
        $moveSqs = parent::moveSqs();
        if ($captureSqs = array_intersect($moveSqs, $this->board->sqCount['used'][$this->oppColor()])) {
            return $captureSqs;
        }

        return $moveSqs;
    }
}
