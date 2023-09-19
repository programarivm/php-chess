<?php

namespace Chess\Eval;

use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Board;

class AbsolutePinEval extends AbstractEval implements InverseEvalInterface
{
    const NAME = 'Absolute pin';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Color::W => 0,
            Color::B => 0,
        ];
    }

    public function eval(): array
    {
        $checkingPieces = $this->board->checkingPieces();
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() !== Piece::K) {
                $clone = msgpack_unpack(msgpack_pack($this->board));
                $clone->detach($clone->getPieceBySq($piece->getSq()));
                $clone->refresh();
                if ($newCheckingPieces = $clone->checkingPieces()) {
                    if ($newCheckingPieces[0]->getColor() !== $piece->getColor() &&
                        count($newCheckingPieces) > count($checkingPieces)
                    ) {
                        $this->result[$piece->getColor()] += $this->value[$piece->getId()];
                    }
                }
            }
        }

        return $this->result;
    }
}
