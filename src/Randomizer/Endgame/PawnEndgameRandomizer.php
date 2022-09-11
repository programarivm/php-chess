<?php

namespace Chess\Randomizer\Endgame;

use Chess\Randomizer\Randomizer;

class PawnEndgameRandomizer extends Randomizer
{
    public function __construct(string $turn)
    {
        $items = [
            $turn => ['P'],
        ];

        do {
            parent::__construct($turn, $items);
            foreach ($this->board->getPieces() as $piece) {
                if ($piece->getId() === 'P') {
                    $ranks = $piece->getRanks();
                }
            }
        } while ($ranks->next === 9 || $ranks->next === 1);
    }
}
