<?php

namespace Chess\Variant\Losing;

use Chess\Variant\AbstractBoard;
use Chess\Variant\RType;
use Chess\Variant\Classical\B;
use Chess\Variant\Classical\N;
use Chess\Variant\Classical\P;
use Chess\Variant\Classical\Q;
use Chess\Variant\Classical\R;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Losing\M;
use Chess\Variant\Losing\PGN\Move;

class Board extends AbstractBoard
{
    public function __construct(array $pieces = null)
    {
        $this->square = new Square();
        $this->move = new Move();
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

    public function isCheck(): bool
    {
        return false;
    }

    protected function xSqs(): array
    {
        $xSqs = [];
        foreach ($this->pieces($this->turn) as $piece) {
            foreach ($piece->attacked() as $attacked) {
                $xSqs[] = $attacked->sq;
            }
        }

        return $xSqs;
    }

    public function legal(string $sq): array
    {
        $moveSqs = $this->pieceBySq($sq)->moveSqs();
        $xSqs = $this->xSqs();
        if ($intersect = array_intersect($moveSqs, $xSqs)) {
            return array_values($intersect);
        } elseif (!$xSqs) {
            return $moveSqs;
        }

        return [];
    }

    public function play(string $color, string $pgn): bool
    {
        if ($xSqs = $this->xSqs()) {
            $move = $this->move->toArray($color, $pgn, $this->square, $this->castlingRule);
            if (in_array($move['to'], $xSqs)) {
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
