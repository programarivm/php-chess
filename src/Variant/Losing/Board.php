<?php

namespace Chess\Variant\Losing;

use Chess\Variant\AbstractBoard;
use Chess\Variant\RType;
use Chess\Variant\VariantType;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;
use Chess\Variant\Losing\Piece\B;
use Chess\Variant\Losing\Piece\M;
use Chess\Variant\Losing\Piece\N;
use Chess\Variant\Losing\Piece\P;
use Chess\Variant\Losing\Piece\Q;
use Chess\Variant\Losing\Piece\R;

class Board extends AbstractBoard
{
    const VARIANT = VariantType::CLASSICAL;

    public function __construct(array $pieces = null) {
        $this->color = new Color();
        $this->square = new Square();
        $this->move = new Move();
        $this->castlingAbility = CastlingRule::NEITHER;
        $this->pieceVariant = VariantType::CLASSICAL;
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
}
