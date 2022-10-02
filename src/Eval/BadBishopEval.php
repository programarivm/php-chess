<?php

namespace Chess\Eval;

use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Board;

class BadBishopEval extends AbstractEval implements InverseEvalInterface
{
    public const NAME = 'Bad bishop';

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
                    Square::color($piece->getSq())
                );
            }
        }

        return $this->result;
    }

    private function count(string $bColor, string $sqColor): int
    {
        $pawns = 0;
        foreach ($this->board->getPieces() as $piece) {
            if (($piece->getId() === Piece::P) && $piece->getColor() === $bColor
                && Square::color($piece->getSq()) === $sqColor) {
                ++$pawns;
            }
        }

        return $pawns;
    }
}
