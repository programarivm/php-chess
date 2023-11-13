<?php

namespace Chess\Play;

use Chess\Exception\PlayException;
use Chess\Movetext\SanMovetext;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\Classical\PGN\Move;

/**
 * Standard Algebraic Notation.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class SanPlay extends AbstractPlay
{
    /**
     * SAN movetext.
     *
     * @var array
     */
    protected SanMovetext $sanMovetext;

    /**
     * Constructor.
     *
     * @param string $movetext
     * @param ClassicalBoard $board
     */
    public function __construct(string $movetext, ClassicalBoard $board = null)
    {
        $this->initialBoard = $board ?? new ClassicalBoard();
        $this->board = unserialize(serialize($board)) ?? new ClassicalBoard();
        $this->fen = [$this->board->toFen()];
        $this->sanMovetext = new SanMovetext($this->board->getMove(), $movetext);
        $this->sanMovetext->validate();
    }

    /**
     * Returns the SAN movetext.
     *
     * @return SanMovetext
     */
    public function getSanMovetext(): SanMovetext
    {
        return $this->sanMovetext;
    }

    /**
     * Semantically validated movetext.
     *
     * Makes the moves in a SAN movetext.
     *
     * @throws \Chess\Exception\PlayException
     * @return \Chess\Play\SanPlay
     */
    public function validate(): SanPlay
    {
        foreach ($this->sanMovetext->getMoves() as $key => $val) {
            if ($val !== Move::ELLIPSIS) {
                if (!$this->board->play($this->board->getTurn(), $val)) {
                    throw new PlayException();
                }
                $this->fen[] = $this->board->toFen();
            }
        }

        return $this;
    }
}
