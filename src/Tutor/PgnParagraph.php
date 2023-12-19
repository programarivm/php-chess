<?php

namespace Chess\Tutor;

/**
 * PgnParagraph
 *
 * Human-like paragraph.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class PgnParagraph
{
    /**
     * FEN paragraph.
     *
     * @var \Chess\Tutor\FenParagraph
     */
    protected FenParagraph $FenParagraph;

    /**
     * The paragraph.
     *
     * @var array
     */
    protected array $paragraph = [];

    /**
     * Constructor.
     *
     * @param string $pgn
     * @param string $fen
     * @param string $variant
     */
    public function __construct(string $pgn, string $fen, string $variant = '')
    {
        $this->fenParagraph = new FenParagraph($fen, $variant);

        $this->explain($pgn, $variant);
    }

    /**
     * Returns the paragraph.
     *
     * @return array
     */
    public function getParagraph(): array
    {
        return $this->paragraph;
    }

    /**
     * Calculates the paragraph.
     *
     * @return \Chess\Tutor\PgnParagraph
     */
    protected function explain(string $pgn, string $variant): PgnParagraph
    {
        $board = $this->fenParagraph->getBoard();
        $board->play($board->getTurn(), $pgn);
        $fenParagraph = new FenParagraph($board->toFen(), $variant);
        foreach ($fenParagraph->getParagraph() as $key => $val) {
            if (!in_array($val, $this->fenParagraph->getParagraph())) {
                $this->paragraph[] = $val;
            }
        }

        return $this;
    }
}
