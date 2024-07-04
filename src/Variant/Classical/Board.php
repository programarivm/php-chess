<?php

namespace Chess\Variant\Classical;

use Chess\Piece\RType;
use Chess\Piece\VariantType;
use Chess\Piece\Classical\B;
use Chess\Piece\Classical\K;
use Chess\Piece\Classical\N;
use Chess\Piece\Classical\P;
use Chess\Piece\Classical\Q;
use Chess\Piece\Classical\R;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

class Board extends AbstractBoard
{
    const VARIANT = 'classical';

    public function __construct(array $pieces = null, string $castlingAbility = '-') {
        $this->color = new Color();
        $this->castlingRule = new CastlingRule();
        $this->square = new Square();
        $this->move = new Move();
        $this->castlingAbility = CastlingRule::START;
        $this->variant = VariantType::CLASSICAL;
        if (!$pieces) {
            $this->attach(new R(Color::W, 'a1', $this->square, RType::CASTLE_LONG));
            $this->attach(new N(Color::W, 'b1', $this->square));
            $this->attach(new B(Color::W, 'c1', $this->square));
            $this->attach(new Q(Color::W, 'd1', $this->square));
            $this->attach(new K(Color::W, 'e1', $this->square));
            $this->attach(new B(Color::W, 'f1', $this->square));
            $this->attach(new N(Color::W, 'g1', $this->square));
            $this->attach(new R(Color::W, 'h1', $this->square, RType::CASTLE_SHORT));
            $this->attach(new P(Color::W, 'a2', $this->square));
            $this->attach(new P(Color::W, 'b2', $this->square));
            $this->attach(new P(Color::W, 'c2', $this->square));
            $this->attach(new P(Color::W, 'd2', $this->square));
            $this->attach(new P(Color::W, 'e2', $this->square));
            $this->attach(new P(Color::W, 'f2', $this->square));
            $this->attach(new P(Color::W, 'g2', $this->square));
            $this->attach(new P(Color::W, 'h2', $this->square));
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

        $this->startFen = $this->toFen();
    }
}
