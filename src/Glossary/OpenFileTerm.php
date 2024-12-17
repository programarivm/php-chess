<?php

namespace Chess\Glossary;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Piece;

class OpenFileTerm extends AbstractTerm
{
    use ElaborateTermTrait;

    const NAME = 'Open file';

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        for ($i = 0; $i < $this->board->square::SIZE['files']; $i++) {
            $this->toElaborate[] = chr(97 + $i);
            for ($j = 0; $j < $this->board->square::SIZE['ranks']; $j++) {
                if ($piece = $this->board->pieceBySq($this->board->square->toAlgebraic($i, $j))) {
                    if ($piece->id === Piece::P) {
                        array_pop($this->toElaborate);
                        break;
                    }
                }
            }
        }
    }

    public function elaborate(): array
    {
        if ($this->toElaborate) {
            $imploded = implode(', ', $this->toElaborate);
            $this->elaboration[] = "These are open files: $imploded.";
        }

        return $this->elaboration;
    }
}
