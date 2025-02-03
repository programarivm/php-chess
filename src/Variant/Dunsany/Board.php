<?php

namespace Chess\Variant\Dunsany;

use Chess\Variant\AbstractBoard;
use Chess\Variant\RType;
use Chess\Variant\Classical\B;
use Chess\Variant\Classical\K;
use Chess\Variant\Classical\N;
use Chess\Variant\Classical\P;
use Chess\Variant\Classical\Q;
use Chess\Variant\Classical\R;
use Chess\Variant\Classical\CastlingRule;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class Board extends AbstractBoard
{
    public function __construct(array $pieces = null, string $castlingAbility = '-')
    {
        $this->castlingRule = new CastlingRule();
        $this->square = new Square();
        $this->move = new Move();
        $this->castlingAbility = substr(CastlingRule::START, -2);
        if (!$pieces) {
            $this->attach(new P(Color::W, 'a1', $this->square));
            $this->attach(new P(Color::W, 'b1', $this->square));
            $this->attach(new P(Color::W, 'c1', $this->square));
            $this->attach(new P(Color::W, 'd1', $this->square));
            $this->attach(new P(Color::W, 'e1', $this->square));
            $this->attach(new P(Color::W, 'f1', $this->square));
            $this->attach(new P(Color::W, 'g1', $this->square));
            $this->attach(new P(Color::W, 'h1', $this->square));
            $this->attach(new P(Color::W, 'a2', $this->square));
            $this->attach(new P(Color::W, 'b2', $this->square));
            $this->attach(new P(Color::W, 'c2', $this->square));
            $this->attach(new P(Color::W, 'd2', $this->square));
            $this->attach(new P(Color::W, 'e2', $this->square));
            $this->attach(new P(Color::W, 'f2', $this->square));
            $this->attach(new P(Color::W, 'g2', $this->square));
            $this->attach(new P(Color::W, 'h2', $this->square));
            $this->attach(new P(Color::W, 'a3', $this->square));
            $this->attach(new P(Color::W, 'b3', $this->square));
            $this->attach(new P(Color::W, 'c3', $this->square));
            $this->attach(new P(Color::W, 'd3', $this->square));
            $this->attach(new P(Color::W, 'e3', $this->square));
            $this->attach(new P(Color::W, 'f3', $this->square));
            $this->attach(new P(Color::W, 'g3', $this->square));
            $this->attach(new P(Color::W, 'h3', $this->square));
            $this->attach(new P(Color::W, 'a4', $this->square));
            $this->attach(new P(Color::W, 'b4', $this->square));
            $this->attach(new P(Color::W, 'c4', $this->square));
            $this->attach(new P(Color::W, 'd4', $this->square));
            $this->attach(new P(Color::W, 'e4', $this->square));
            $this->attach(new P(Color::W, 'f4', $this->square));
            $this->attach(new P(Color::W, 'g4', $this->square));
            $this->attach(new P(Color::W, 'h4', $this->square));
            $this->attach(new R(Color::B, 'a8', $this->square, RType::CASTLE_LONG));
            $this->attach(new N(Color::B, 'b8', $this->square));
            $this->attach(new B(Color::B, 'c8', $this->square));
            $this->attach(new Q(Color::B, 'd8', $this->square));
            $this->attach(new K(Color::B, 'e8', $this->square));
            $this->attach(new B(Color::B, 'f8', $this->square));
            $this->attach(new N(Color::B, 'g8', $this->square));
            $this->attach(new R(Color::B, 'h8', $this->square, RType::CASTLE_SHORT));
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
            $this->castlingAbility = $castlingAbility;
        }
        $this->refresh();
        $this->turn = Color::B;
        $this->startFen = $this->toFen();
    }

    public function isCheck(): bool
    {
        return $this->piece($this->turn, Piece::K)?->attacking() !== [];
    }

    public function isStalemate(): bool
    {
        if (!$this->doesWin()) {
            $moveSqs = [];
            foreach ($this->pieces($this->turn) as $piece) {
                $moveSqs = [
                    ...$moveSqs,
                    ...$piece->moveSqs(),
                ];
            }
            return $moveSqs === [];
        }

        return false;
    }

    public function doesWin(): bool
    {
        return $this->isMate() xor $this->pieces(Color::W) === [];
    }
}
