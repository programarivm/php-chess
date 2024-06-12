<?php

namespace Chess\Randomizer\Checkmate;

use Chess\Randomizer\Randomizer;

class TwoBishopsRandomizer extends Randomizer
{
    public function __construct(string $turn)
    {
        $items = [
            $turn => ['B', 'B'],
        ];

        do {
            parent::__construct($turn, $items);
            $colors = '';
            foreach ($this->board->getPieces($turn) as $piece) {
                if ($piece->id === 'B') {
                    $colors .= $this->board->square->color($piece->sq);
                }
            }
        } while ($colors === 'ww' || $colors === 'bb');
    }
}
