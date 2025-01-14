<?php

namespace Chess\Glossary;

use Chess\Phrase\ColorPhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

class ConnectedRooksTerm extends AbstractTerm
{
    use ElaborateTermTrait;

    const NAME = 'Connected rooks';

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        foreach ($this->board->pieces(Color::W) as $piece) {
            if ($piece->id === Piece::R) {
                foreach ($piece->defended() as $val) {
                    if ($val->id === Piece::R) {
                        $this->toElaborate[] = $piece;
                        break 2;
                    }
                }
            }
        }

        foreach ($this->board->pieces(Color::B) as $piece) {
            if ($piece->id === Piece::R) {
                foreach ($piece->defended() as $val) {
                    if ($val->id === Piece::R) {
                        $this->toElaborate[] = $piece;
                        break 2;
                    }
                }
            }
        }
    }

    public function elaborate(): array
    {
        foreach ($this->toElaborate as $val) {
            $phrase = ColorPhrase::sentence($val->color);
            $this->elaboration[] = ucfirst("$phrase has connected rooks.");
        }

        return $this->elaboration;
    }
}
