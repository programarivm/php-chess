<?php

namespace Chess\Play;

use Chess\Exception\UnknownNotationException;
use Chess\Movetext\SanMovetext;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\Move;

class SanPlay extends AbstractPlay
{
    public SanMovetext $sanMovetext;

    public function __construct(string $movetext, AbstractBoard $board = null)
    {
        if ($board) {
            $this->initialBoard = $board;
            $this->board = $board;
        } else {
            $this->initialBoard = new Board();
            $this->board = new Board();
        }
        $this->fen = [$this->board->toFen()];
        $this->sanMovetext = new SanMovetext($this->board->move, $movetext);
    }

    public function validate(): SanPlay
    {
        foreach ($this->sanMovetext->moves as $key => $val) {
            if ($val !== Move::ELLIPSIS) {
                if (!$this->board->play($this->board->turn, $val)) {
                    throw new UnknownNotationException();
                }
                $this->fen[] = $this->board->toFen();
            }
        }

        return $this;
    }
}
