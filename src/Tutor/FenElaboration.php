<?php

namespace Chess\Tutor;

use Chess\StandardFunction;
use Chess\Eval\ElaborateEvalInterface;
use Chess\Variant\Classical\Board;

/**
 * FenElaboration
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class FenElaboration extends AbstractParagraph
{
    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;

        foreach ((new StandardFunction())->getEval() as $val) {
            $eval = new $val($this->board);
            if (is_a($eval, ElaborateEvalInterface::class)) {
                if ($phrases = $eval->getElaboration()) {
                    $this->paragraph = [...$this->paragraph, ...$phrases];
                }
            }
        }
    }
}
