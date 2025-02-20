<?php

namespace Chess\Play;

use Chess\Exception\UnknownNotationException;
use Chess\Movetext\SanMovetext;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\Move;

/**
 * SAN Play
 *
 * Semantic validation for games in Standard Algebraic Notation.
 */
class SanPlay extends AbstractPlay
{
    /**
     * SAN movetext.
     *
     * @var \Chess\Movetext\SanMovetext
     */
    public SanMovetext $sanMovetext;

    /**
     * @param string $movetext
     * @param \Chess\Variant\AbstractBoard $board
     */
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

    /**
     * Semantic validation.
     * 
     * @return \Chess\Play\SanPlay
     */
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
