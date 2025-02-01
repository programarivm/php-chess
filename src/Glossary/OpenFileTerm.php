<?php

namespace Chess\Glossary;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Piece;

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
                if ($piece = $this->board->pieceBySq($this->board->square->toAn($i, $j))) {
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
        $count = count($this->toElaborate);
        if ($count > 0 && $count < 4) {
            $imploded = implode(', ', $this->toElaborate);
            $this->elaboration[] = "These are open files with no pawns of either color on it: $imploded.";
        }

        return $this->elaboration;
    }
}
