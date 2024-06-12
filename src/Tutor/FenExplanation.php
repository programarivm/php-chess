<?php

namespace Chess\Tutor;

use Chess\StandardFunction;
use Chess\Eval\ExplainEvalInterface;
use Chess\Variant\Classical\Board;

/**
 * FenExplanation
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class FenExplanation extends AbstractParagraph
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
            if (is_a($eval, ExplainEvalInterface::class)) {
                if ($phrases = $eval->getExplanation()) {
                    $this->paragraph = [...$this->paragraph, ...$phrases];
                }
            }
        }
    }
}
