<?php

namespace Chess\Media;

use Chess\FenHeuristics;
use Chess\Exception\MediaException;
use Chess\Function\FastFunction;
use Chess\ML\GoldbachLabeller;
use Chess\Movetext\SanMovetext;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;

class BoardToSignal
{
    const MAX_MOVES = 300;

    public array $signal;

    public function __construct(string $movetext, AbstractBoard $board)
    {
        $sanMovetext = new SanMovetext($board->move, $movetext);

        if (!$sanMovetext->validate()) {
            throw new MediaException();
        }

        if (self::MAX_MOVES < count($sanMovetext->moves)) {
            throw new MediaException();
        }

        foreach ($sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if (!$board->play($board->turn, $val)) {
                    throw new MediaException();
                }
                $this->signal[] = (new GoldbachLabeller())
                    ->label((new FenHeuristics(new FastFunction(), $board))->balance);
            }
        }
    }
}
