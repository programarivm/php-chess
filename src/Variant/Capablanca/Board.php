<?php

namespace Chess\Variant\Capablanca;

use Chess\Piece\A;
use Chess\Piece\C;
use Chess\Piece\B;
use Chess\Piece\K;
use Chess\Piece\N;
use Chess\Piece\P;
use Chess\Piece\Q;
use Chess\Piece\R;
use Chess\Piece\RType;
use Chess\Variant\Capablanca\FEN\StrToBoard;
use Chess\Variant\Capablanca\Rule\CastlingRule;
use Chess\Variant\Capablanca\PGN\Move;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Classical\FEN\Field\CastlingAbility;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\Board as ClassicalBoard;

/**
 * Board
 *
 * Chess board representation to play Capablanca chess on a 10×8 board.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class Board extends ClassicalBoard
{
    const VARIANT = 'capablanca';

    /**
     * Constructor.
     *
     * @param array $pieces
     * @param string $castlingAbility
     */
    public function __construct(
        array $pieces = null,
        string $castlingAbility = '-'
    ) {
        $this->size = Square::SIZE;
        $this->sqs = Square::all();
        $this->castlingAbility = CastlingAbility::START;
        $this->castlingRule = (new CastlingRule())->getRule();
        $this->move = new Move();
        if (!$pieces) {
            $this->attach(new R(Color::W, 'a1', $this->size, RType::CASTLE_LONG));
            $this->attach(new N(Color::W, 'b1', $this->size));
            $this->attach(new A(Color::W, 'c1', $this->size));
            $this->attach(new B(Color::W, 'd1', $this->size));
            $this->attach(new Q(Color::W, 'e1', $this->size));
            $this->attach(new K(Color::W, 'f1', $this->size));
            $this->attach(new B(Color::W, 'g1', $this->size));
            $this->attach(new C(Color::W, 'h1', $this->size));
            $this->attach(new N(Color::W, 'i1', $this->size));
            $this->attach(new R(Color::W, 'j1', $this->size, RType::CASTLE_SHORT));
            $this->attach(new P(Color::W, 'a2', $this->size));
            $this->attach(new P(Color::W, 'b2', $this->size));
            $this->attach(new P(Color::W, 'c2', $this->size));
            $this->attach(new P(Color::W, 'd2', $this->size));
            $this->attach(new P(Color::W, 'e2', $this->size));
            $this->attach(new P(Color::W, 'f2', $this->size));
            $this->attach(new P(Color::W, 'g2', $this->size));
            $this->attach(new P(Color::W, 'h2', $this->size));
            $this->attach(new P(Color::W, 'i2', $this->size));
            $this->attach(new P(Color::W, 'j2', $this->size));
            $this->attach(new R(Color::B, 'a8', $this->size, RType::CASTLE_LONG));
            $this->attach(new N(Color::B, 'b8', $this->size));
            $this->attach(new A(Color::B, 'c8', $this->size));
            $this->attach(new B(Color::B, 'd8', $this->size));
            $this->attach(new Q(Color::B, 'e8', $this->size));
            $this->attach(new K(Color::B, 'f8', $this->size));
            $this->attach(new B(Color::B, 'g8', $this->size));
            $this->attach(new C(Color::B, 'h8', $this->size));
            $this->attach(new N(Color::B, 'i8', $this->size));
            $this->attach(new R(Color::B, 'j8', $this->size, RType::CASTLE_SHORT));
            $this->attach(new P(Color::B, 'a7', $this->size));
            $this->attach(new P(Color::B, 'b7', $this->size));
            $this->attach(new P(Color::B, 'c7', $this->size));
            $this->attach(new P(Color::B, 'd7', $this->size));
            $this->attach(new P(Color::B, 'e7', $this->size));
            $this->attach(new P(Color::B, 'f7', $this->size));
            $this->attach(new P(Color::B, 'g7', $this->size));
            $this->attach(new P(Color::B, 'h7', $this->size));
            $this->attach(new P(Color::B, 'i7', $this->size));
            $this->attach(new P(Color::B, 'j7', $this->size));
        } else {
            foreach ($pieces as $piece) {
                $this->attach($piece);
            }
            $this->castlingAbility = $castlingAbility;
        }

        $this->refresh();

        $this->startFen = $this->toFen();
    }

    /**
     * Undoes the last move.
     *
     * @return \Chess\Variant\Capablanca\Board
     */
    public function undo(): Board
    {
        $board = (new StrToBoard($this->getStartFen()))->create();
        foreach ($this->popHistory()->getHistory() as $key => $val) {
            $board->play($val->move->color, $val->move->pgn);
        }

        return $board;
    }
}
