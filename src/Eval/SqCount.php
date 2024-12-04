<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;

class SqCount
{
    const TYPE_FREE      = 'free';
    const TYPE_USED      = 'used';

    public static function count(AbstractBoard $board)
    {
        $used = [
            Color::W => [],
            Color::B => [],
        ];

        foreach ($board->pieces() as $piece) {
            $used[$piece->color][] = $piece->sq;
        }

        return [
            self::TYPE_FREE => array_diff(
                $board->square->all(), 
                [...$used[Color::W], ...$used[Color::B]]
            ),
            self::TYPE_USED => $used,
        ];
    }
}
