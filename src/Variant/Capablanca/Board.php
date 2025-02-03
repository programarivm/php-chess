<?php

namespace Chess\Variant\Capablanca;

use Chess\Variant\AbstractBoard;
use Chess\Variant\RType;
use Chess\Variant\Capablanca\A;
use Chess\Variant\Capablanca\C;
use Chess\Variant\Capablanca\CastlingRule;
use Chess\Variant\Capablanca\PGN\Move;
use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\Classical\B;
use Chess\Variant\Classical\K;
use Chess\Variant\Classical\N;
use Chess\Variant\Classical\P;
use Chess\Variant\Classical\Q;
use Chess\Variant\Classical\R;
use Chess\Variant\Classical\PGN\Color;

class Board extends AbstractBoard
{
    public function __construct(array $pieces = null, string $castlingAbility = '-')
    {
        $this->castlingRule = new CastlingRule();
        $this->square = new Square();
        $this->move = new Move();
        $this->castlingAbility = CastlingRule::START;
        if (!$pieces) {
            $this->attach(new R(Color::W, 'a1', $this->square, RType::CASTLE_LONG));
            $this->attach(new N(Color::W, 'b1', $this->square));
            $this->attach(new A(Color::W, 'c1', $this->square));
            $this->attach(new B(Color::W, 'd1', $this->square));
            $this->attach(new Q(Color::W, 'e1', $this->square));
            $this->attach(new K(Color::W, 'f1', $this->square));
            $this->attach(new B(Color::W, 'g1', $this->square));
            $this->attach(new C(Color::W, 'h1', $this->square));
            $this->attach(new N(Color::W, 'i1', $this->square));
            $this->attach(new R(Color::W, 'j1', $this->square, RType::CASTLE_SHORT));
            $this->attach(new P(Color::W, 'a2', $this->square));
            $this->attach(new P(Color::W, 'b2', $this->square));
            $this->attach(new P(Color::W, 'c2', $this->square));
            $this->attach(new P(Color::W, 'd2', $this->square));
            $this->attach(new P(Color::W, 'e2', $this->square));
            $this->attach(new P(Color::W, 'f2', $this->square));
            $this->attach(new P(Color::W, 'g2', $this->square));
            $this->attach(new P(Color::W, 'h2', $this->square));
            $this->attach(new P(Color::W, 'i2', $this->square));
            $this->attach(new P(Color::W, 'j2', $this->square));
            $this->attach(new R(Color::B, 'a8', $this->square, RType::CASTLE_LONG));
            $this->attach(new N(Color::B, 'b8', $this->square));
            $this->attach(new A(Color::B, 'c8', $this->square));
            $this->attach(new B(Color::B, 'd8', $this->square));
            $this->attach(new Q(Color::B, 'e8', $this->square));
            $this->attach(new K(Color::B, 'f8', $this->square));
            $this->attach(new B(Color::B, 'g8', $this->square));
            $this->attach(new C(Color::B, 'h8', $this->square));
            $this->attach(new N(Color::B, 'i8', $this->square));
            $this->attach(new R(Color::B, 'j8', $this->square, RType::CASTLE_SHORT));
            $this->attach(new P(Color::B, 'a7', $this->square));
            $this->attach(new P(Color::B, 'b7', $this->square));
            $this->attach(new P(Color::B, 'c7', $this->square));
            $this->attach(new P(Color::B, 'd7', $this->square));
            $this->attach(new P(Color::B, 'e7', $this->square));
            $this->attach(new P(Color::B, 'f7', $this->square));
            $this->attach(new P(Color::B, 'g7', $this->square));
            $this->attach(new P(Color::B, 'h7', $this->square));
            $this->attach(new P(Color::B, 'i7', $this->square));
            $this->attach(new P(Color::B, 'j7', $this->square));
        } else {
            foreach ($pieces as $piece) {
                $this->attach($piece);
            }
            $this->castlingAbility = $castlingAbility;
        }
        $this->refresh();
        $this->startFen = $this->toFen();
    }
}
