<?php

namespace Chess;

use Chess\EvalArray;
use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;

class SanDecoder
{
    public AbstractFunction $f;

    public array $mean;

    public AbstractBoard $board;

    public function __construct(AbstractFunction $f, AbstractBoard $board, array $mean)
    {
        $this->f = $f;
        $this->board = $board;
        $this->mean = $mean;

        for ($i = 1; $i < count($this->mean); $i++) {
            if ($move = $this->move($i, $this->board)) {
                $this->board->playLan($this->board->turn, $move);
            }
        }
    }

    protected function move($n, $board): bool|string
    {
        foreach ($this->board->pieces($this->board->turn) as $piece) {
            foreach ($piece->moveSqs() as $sq) {
                $clone = $this->board->clone();
                if ($clone->playLan($clone->turn, "{$piece->sq}$sq")) {
                    if (EvalArray::mean($this->f, $clone) === $this->mean[$n]) {
                        return "{$piece->sq}$sq";
                    }
                }
            }
        }

        return false;
    }
}
