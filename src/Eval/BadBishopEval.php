<?php

namespace Chess\Eval;

use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Board;

class BadBishopEval extends AbstractEval implements InverseEvalInterface
{
    const NAME = 'Bad bishop';

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
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::B) {
                $this->result[$piece->getColor()] += $this->count(
                    $piece->getColor(),
                    $this->isValidSqColor($piece->getSq())
                );
            }
        }

        return $this->result;
    }

    private function count(string $bColor, string $sqColor)
    {
        $pawns = 0;
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::P) {
                if (
                    $piece->getColor() === $bColor &&
                    $this->isValidSqColor($piece->getSq()) === $sqColor
                ) {
                    $pawns += 1;
                }
            }
        }

        return $pawns;
    }

    protected function isValidSqColor(string $sq)
    {
        // TODO: Refactor this if statement
        if ($this->board->getSize() === ['files' => 8, 'ranks' => 8]) {
            return \Chess\Variant\Classical\PGN\AN\Square::color($sq);
        } elseif ($this->board->getSize() === ['files' => 10, 'ranks' => 8]) {
            return \Chess\Variant\Capablanca\PGN\AN\Square::color($sq);
        } elseif ($this->board->getSize() === ['files' => 10, 'ranks' => 10]) {
            return \Chess\Variant\Capablanca\PGN\AN\Square::color($sq);
        }

        return false;
    }
}
