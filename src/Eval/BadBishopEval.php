<?php

declare(strict_types=1);

namespace Chess\Eval;

use Chess\Board;
use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;
use Chess\Piece\AbstractPiece;

class BadBishopEval extends AbstractForkEval
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
        /** @var AbstractPiece $piece */
        foreach ($this->board->getPieces() as $piece) {
            $color = $piece->getColor();
            if ($piece->getId() === Piece::B) {
              $mobility = $piece->getMobility();

              $closestPossibleMobility = array_reduce((array) $mobility, static function($carry, $key) {
                  if (array_key_exists(0, $key)) {
                      $carry[] = $key[0];
                  }
                  return $carry;
              }, []);

              $pawns = array_map(fn($field) => $this->board->getPieceBySq($field), $closestPossibleMobility);
              $ownPawns = array_filter($pawns, fn($pawn) => $pawn?->getColor() === $color);

              if ($ownPawns >= 3) {
                  $this->result[$color] += 1;
              }
            }
        }

        return $this->result;
    }
}
