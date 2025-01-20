<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\Classical\K as ClassicalK;

class K extends ClassicalK
{
    /**
     * Returns the piece's moves.
     * 
     * Unlike in classical chess, in Chess960 there are positions where the
     * king can be moved to the same square where it is located in order to
     * castle. Castling is thus possible by double-clicking on the square
     * where the king is currently located.
     *
     * @return array
     */
    public function moveSqs(): array
    {
        $sqs = [
            ...$this->moveSqs(),
            ...[$this->sq],
        ];

        return array_filter(array_unique($sqs));
    }
}
