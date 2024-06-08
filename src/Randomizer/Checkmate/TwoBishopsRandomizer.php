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
                if ($piece->getId() === 'B') {
                    $colors .= $this->board->getSquare()->color($piece->getSq());
                }
            }
        } while ($colors === 'ww' || $colors === 'bb');
    }
}
