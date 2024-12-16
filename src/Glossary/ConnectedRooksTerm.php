<?php

namespace Chess\Glossary;

use Chess\Tutor\ColorPhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Piece;

class ConnectedRooksTerm extends AbstractTerm
{
    use ElaborateTermTrait;

    const NAME = 'Connected rooks';

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::R) {
                foreach ($piece->defended() as $val) {
                    if ($val->id === Piece::R) {
                        $this->toElaborate[] = $this->elaborate($piece);
                        break;
                    }
                }
            }
        }
    }

    public function elaborate(): array
    {
        foreach ($this->toElaborate as $val) {
            $phrase = ColorPhrase::sentence($val->oppColor());
            $this->elaboration[] = ucfirst("$phrase has connected rooks.");
        }

        return $this->elaboration;
    }
}
