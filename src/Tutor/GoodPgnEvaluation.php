<?php

namespace Chess\Tutor;

use Chess\UciEngine\UciEngine;
use Chess\UciEngine\Details\Limit;
use Chess\Variant\Classical\Board;

/**
 * GoodPgnEvaluation
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class GoodPgnEvaluation extends AbstractParagraph
{
    /**
     * UCI engine limit.
     *
     * @var \Chess\UciEngine\Details\Limit
     */
    private Limit $limit;

    /**
     * UCI engine.
     *
     * @var \Chess\UciEngine\Details\UciEngine
     */
    private UciEngine $uciEngine;

    /**
     * PGN move.
     *
     * @var string
     */
    private string $pgn;

    /**
     * Constructor.
     *
     * @param \Chess\UciEngine\Details\Limit $limit
     * @param \Chess\UciEngine\Details\UciEngine $uciEngine
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Limit $limit, UciEngine $uciEngine, Board $board)
    {
        $this->limit = $limit;
        $this->uciEngine = $uciEngine;
        $this->board = $board;

        $analysis = $uciEngine->analysis($this->board, $limit);

        $clone = $this->board->clone();
        $clone->playLan($clone->turn, $analysis['bestmove']);

        $last = array_slice($clone->history, -1)[0];

        $this->pgn = $last['move']['pgn'];

        $this->paragraph = (new PgnEvaluation($this->pgn, $this->board))->getParagraph();
    }

    /**
     * Returns the UCI engine limit.
     *
     * @return \Chess\UciEngine\Details\Limit
     */
    public function getLimit(): Limit
    {
        return $this->limit;
    }

    /**
     * Returns the UCI engine.
     *
     * @return \Chess\UciEngine\Details\UciEngine
     */
    public function getUciEngine(): UciEngine
    {
        return $this->uciEngine;
    }

    /**
     * Returns the PGN move.
     *
     * @return string
     */
    public function getPgn(): string
    {
        return $this->pgn;
    }
}
