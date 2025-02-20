<?php

namespace Chess\Play;

use Chess\Exception\UnknownNotationException;
use Chess\Movetext\RavMovetext;
use Chess\Movetext\SanMovetext;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\FenToBoardFactory;

/**
 * RAV Play
 *
 * Semantic validation for games in Recursive Annotation Variation.
 */
class RavPlay extends AbstractPlay
{
    /**
     * RAV movetext.
     *
     * @var \Chess\Movetext\RavMovetext
     */
    public RavMovetext $ravMovetext;

    /**
     * Resume the variations.
     *
     * @var array
     */
    protected array $resume;

    /**
     * Constructor.
     *
     * @param string $movetext
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(string $movetext, AbstractBoard $board = null)
    {
        if ($board) {
            $this->initialBoard = $board;
            $this->board = $board->clone();
        } else {
            $this->initialBoard = new Board();
            $this->board = new Board();
        }
        $this->fen = [$this->board->toFen()];
        $this->ravMovetext = new RavMovetext($this->board->move, $movetext);
    }

    /**
     * Semantic validation for the main variation.
     *
     * @throws \Chess\Exception\UnknownNotationException
     * @return \Chess\Play\RavPlay
     */
    public function validate(): RavPlay
    {
        $moves = (new SanMovetext(
            $this->ravMovetext->move,
            $this->ravMovetext->main()
        ))->moves;

        foreach ($moves as $key => $val) {
            if (!$this->board->play($this->board->turn, $val)) {
                throw new UnknownNotationException();
            }
        }

        $this->fen();

        return $this;
    }

    /**
     * Calculates the FEN history.
     *
     * @return \Chess\Play\RavPlay
     */
    protected function fen(): RavPlay
    {
        $sanPlay = (new SanPlay(
            $this->ravMovetext->breakdown[0],
            $this->initialBoard
        ))->validate();
        $this->fen = $sanPlay->fen;
        $this->resume[$sanPlay->sanMovetext->filtered(false, false)] = $sanPlay->board;
        for ($i = 1; $i < count($this->ravMovetext->breakdown); $i++) {
            $sanMovetext = new SanMovetext(
                $this->ravMovetext->move,
                $this->ravMovetext->breakdown[$i]
            );
            foreach ($this->resume as $key => $val) {
                $sanMovetextKey = new SanMovetext($this->ravMovetext->move, $key);
                if ($this->ravMovetext->isPrevious($sanMovetextKey, $sanMovetext)) {
                    $board = $this->isUndo($sanMovetextKey->metadata['lastMove'], $sanMovetext->metadata['firstMove'])
                        ? FenToBoardFactory::create($val->clone()->undo()->toFen(), $this->initialBoard)
                        : FenToBoardFactory::create($val->toFen(), $this->initialBoard);
                }
            }
            $sanPlay = (new SanPlay($this->ravMovetext->breakdown[$i], $board))->validate();
            $this->resume[$sanPlay->sanMovetext->filtered(false, false)] = $sanPlay->board;
            $fen = $sanPlay->fen;
            array_shift($fen);
            $this->fen = [
                ...$this->fen,
                ...$fen,
            ];
        }

        return $this;
    }

    /**
     * Finds out if a move must be undone.
     *
     * @param string $previous
     * @param string $current
     * @return bool
     */
    protected function isUndo(string $previous, string $current): bool
    {
        $previous = new SanMovetext($this->ravMovetext->move, $previous);
        $current = new SanMovetext($this->ravMovetext->move, $current);

        return $previous->metadata['turn'] === $current->metadata['turn'];
    }
}
