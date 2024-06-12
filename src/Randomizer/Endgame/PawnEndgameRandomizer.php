<?php

namespace Chess\Randomizer\Endgame;

use Chess\Randomizer\Randomizer;
use Chess\Variant\Classical\PGN\AN\Color;

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
                if ($piece->id === 'P') {
                    $ranks = $piece->getRanks();
                }
            }
        } while (
            $turn === Color::W && ($ranks['next'] === 2 || $ranks['next'] === 9) ||
            $turn === Color::B && ($ranks['next'] === 7 || $ranks['next'] === 0)
        );
    }
}
