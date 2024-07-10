<?php

namespace Chess\Variant\Dunsany;

use Chess\Variant\AbstractBoard;
use Chess\Variant\RType;
use Chess\Variant\VariantType;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Piece\B;
use Chess\Variant\Classical\Piece\K;
use Chess\Variant\Classical\Piece\N;
use Chess\Variant\Classical\Piece\P;
use Chess\Variant\Classical\Piece\Q;
use Chess\Variant\Classical\Piece\R;
use Chess\Variant\Classical\Rule\CastlingRule;

class Board extends AbstractBoard
{
    const VARIANT = 'dunsany';

    public function __construct(array $pieces = null, string $castlingAbility = '-') {
        $this->color = new Color();
        $this->castlingRule = new CastlingRule();
        $this->square = new Square();
        $this->move = new Move();
        $this->castlingAbility = substr(CastlingRule::START, -2);
        $this->pieceVariant = VariantType::CLASSICAL;
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

    public function isWon()
    {
        return $this->isMate() xor empty($this->pieces(Color::W));
    }

    public function isStalemate(): bool
    {
        if (Color::W === $this->turn) {
            return $this->isTrapped() && !$this->isCheck() && !$this->isWon();
        }

        $legal = [];
        $clone = $this->clone();
        $clone->turn = Color::W;
        foreach ($clone->pieces(Color::W) as $piece) {
            $legal = [
                ...$legal,
                ...$clone->legal($piece->sq),
            ];
        }

        return empty($legal);
    }
}
