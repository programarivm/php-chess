<?php

namespace Chess\Computer;

use Chess\Variant\AbstractBoard;

class RandomMove
{
    protected AbstractBoard $board;

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;
    }

    public function move(): ?array
    {
        $legal = [];
        foreach ($this->board->pieces($this->board->turn) as $piece) {
            if ($sqs = $piece->moveSqs()) {
                $legal[$piece->sq] = $sqs;
            }
        }

        $from = array_rand($legal);
        shuffle($legal[$from]);
        $to = $legal[$from][0];

        $lan = "{$from}{$to}";

        if ($this->board->playLan($this->board->turn, $lan)) {
            $last = array_slice($this->board->history, -1)[0];
            return [
                'pgn' => $last['pgn'],
                'lan' => $lan,
            ];
        }

        return null;
    }
}
