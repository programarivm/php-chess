<?php

namespace Chess\Randomizer\Checkmate;

use Chess\PGN\AN\Square;
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
            foreach ($this->board->getPiecesByColor($turn) as $piece) {
                if ($piece->getId() === 'B') {
                    $colors .= Square::color($piece->getSq());
                }
            }
        } while ($colors === 'ww' || $colors === 'bb');
    }
}
