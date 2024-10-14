<?php

namespace Chess\Variant\Losing;

use Chess\Variant\AbstractBoard;
use Chess\Variant\RType;
use Chess\Variant\VariantType;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Piece\B;
use Chess\Variant\Classical\Piece\N;
use Chess\Variant\Classical\Piece\P;
use Chess\Variant\Classical\Piece\Q;
use Chess\Variant\Classical\Piece\R;
use Chess\Variant\Losing\PGN\Move;
use Chess\Variant\Losing\Piece\M;

class Board extends AbstractBoard
{
    const VARIANT = VariantType::LOSING;

    public function __construct(array $pieces = null)
    {
        $this->color = new Color();
        $this->square = new Square();
        $this->move = new Move();
        $this->pieceVariant = VariantType::LOSING;
        if (!$pieces) {
            $this->attach(new R(Color::W, 'a1', $this->square, RType::R));
            $this->attach(new N(Color::W, 'b1', $this->square));
            $this->attach(new B(Color::W, 'c1', $this->square));
            $this->attach(new Q(Color::W, 'd1', $this->square));
            $this->attach(new M(Color::W, 'e1', $this->square));
            $this->attach(new B(Color::W, 'f1', $this->square));
            $this->attach(new N(Color::W, 'g1', $this->square));
            $this->attach(new R(Color::W, 'h1', $this->square, RType::R));
            $this->attach(new P(Color::W, 'a2', $this->square));
            $this->attach(new P(Color::W, 'b2', $this->square));
            $this->attach(new P(Color::W, 'c2', $this->square));
            $this->attach(new P(Color::W, 'd2', $this->square));
            $this->attach(new P(Color::W, 'e2', $this->square));
            $this->attach(new P(Color::W, 'f2', $this->square));
            $this->attach(new P(Color::W, 'g2', $this->square));
            $this->attach(new P(Color::W, 'h2', $this->square));
            $this->attach(new R(Color::B, 'a8', $this->square, RType::R));
            $this->attach(new N(Color::B, 'b8', $this->square));
            $this->attach(new B(Color::B, 'c8', $this->square));
            $this->attach(new Q(Color::B, 'd8', $this->square));
            $this->attach(new M(Color::B, 'e8', $this->square));
            $this->attach(new B(Color::B, 'f8', $this->square));
            $this->attach(new N(Color::B, 'g8', $this->square));
            $this->attach(new R(Color::B, 'h8', $this->square, RType::R));
            $this->attach(new P(Color::B, 'a7', $this->square));
            $this->attach(new P(Color::B, 'b7', $this->square));
            $this->attach(new P(Color::B, 'c7', $this->square));
            $this->attach(new P(Color::B, 'd7', $this->square));
            $this->attach(new P(Color::B, 'e7', $this->square));
            $this->attach(new P(Color::B, 'f7', $this->square));
            $this->attach(new P(Color::B, 'g7', $this->square));
            $this->attach(new P(Color::B, 'h7', $this->square));
        } else {
            foreach ($pieces as $piece) {
                $this->attach($piece);
            }
        }

        $this->refresh();

        $this->startFen = $this->toFen();
    }

    protected function captureSqs(): array
    {
        $captureSqs = [];
        foreach ($this->pieces($this->turn) as $piece) {
            foreach ($piece->attacked() as $attacked) {
                $captureSqs[] = $attacked->sq;
            }
        }

        return $captureSqs;
    }

    public function legal(string $sq): array
    {
        $moveSqs = $this->pieceBySq($sq)->moveSqs();
        $captureSqs = $this->captureSqs();
        if ($intersect = array_intersect($moveSqs, $captureSqs)) {
            return array_values($intersect);
        } elseif (!$captureSqs) {
            return $moveSqs;
        }

        return [];
    }

    public function play(string $color, string $pgn): bool
    {
        if ($captureSqs = $this->captureSqs()) {
            $move = $this->move->toArray($color, $pgn, $this->castlingRule, $this->color);
            if (in_array($move['sq']['next'], $captureSqs)) {
                return parent::play($color, $pgn);
            }
        } else {
            return parent::play($color, $pgn);
        }

        return false;
    }

    public function isStalemate(): bool
    {
        $moveSqs = [];
        foreach ($this->pieces($this->turn) as $piece) {
            $moveSqs = [
                ...$moveSqs,
                ...$piece->moveSqs(),
            ];
        }

        return $moveSqs === [];
    }

    public function doesWin(): bool
    {
        return count($this->pieces($this->turn)) === 0 || $this->isStalemate();
    }
}
