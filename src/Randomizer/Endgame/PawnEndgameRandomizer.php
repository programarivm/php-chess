<?php

namespace Chess\Randomizer\Endgame;

use Chess\Randomizer\Randomizer;
use Chess\Variant\Classical\PGN\Color;

class PawnEndgameRandomizer extends Randomizer
{
    public function __construct(string $turn)
    {
        $items = [
            $turn => ['P'],
        ];

        do {
            parent::__construct($turn, $items);
            foreach ($this->board->pieces() as $piece) {
                if ($piece->id === 'P') {
                    $nextRank = $piece->nextRank();
                }
            }
        } while (
            $turn === Color::W && ($nextRank === 2 || $nextRank === 9) ||
            $turn === Color::B && ($nextRank === 7 || $nextRank === 0)
        );
    }
}
