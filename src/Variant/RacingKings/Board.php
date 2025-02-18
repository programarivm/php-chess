<?php

namespace Chess\Variant\RacingKings;

use Chess\Variant\AbstractBoard;
use Chess\Variant\RType;
use Chess\Variant\Classical\B;
use Chess\Variant\Classical\K;
use Chess\Variant\Classical\N;
use Chess\Variant\Classical\Q;
use Chess\Variant\Classical\R;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\Classical\FenToBoardFactory;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Classical\PGN\Piece;

class Board extends AbstractBoard
{
    public function __construct(array $pieces = null)
    {
        $this->square = new Square();
        $this->move = new Move();
        if (!$pieces) {
            $this->attach(new Q(Color::B, 'a1', $this->square));
            $this->attach(new R(Color::B, 'b1', $this->square, RType::R));
            $this->attach(new B(Color::B, 'c1', $this->square));
            $this->attach(new N(Color::B, 'd1', $this->square));
            $this->attach(new N(Color::W, 'e1', $this->square));
            $this->attach(new B(Color::W, 'f1', $this->square));
            $this->attach(new R(Color::W, 'g1', $this->square, RType::R));
            $this->attach(new Q(Color::W, 'h1', $this->square));
            $this->attach(new K(Color::B, 'a2', $this->square));
            $this->attach(new R(Color::B, 'b2', $this->square, RType::R));
            $this->attach(new B(Color::B, 'c2', $this->square));
            $this->attach(new N(Color::B, 'd2', $this->square));
            $this->attach(new N(Color::W, 'e2', $this->square));
            $this->attach(new B(Color::W, 'f2', $this->square));
            $this->attach(new R(Color::W, 'g2', $this->square, RType::R));
            $this->attach(new K(Color::W, 'h2', $this->square));
        } else {
            foreach ($pieces as $piece) {
                $this->attach($piece);
            }
        }
        $this->refresh();
        $this->startFen = $this->toFen();
    }

    public function play(string $color, string $pgn): bool
    {
        $board = FenToBoardFactory::create($this->toFen(), new ClassicalBoard());
        if ($board->play($color, $pgn)) {
            if (!$board->isCheck()) {
                return parent::play($color, $pgn);
            }
        }

        return false;
    }

    public function doesWin(): bool
    {
        $wKing = $this->piece(Color::W, Piece::K);
        $bKing = $this->piece(Color::B, Piece::K);
        $wWins = $wKing->rank() === $this->square::SIZE['ranks'] &&
            $bKing->rank() !== $this->square::SIZE['ranks'];
        $bWins = $wKing->rank() !== $this->square::SIZE['ranks'] &&
            $bKing->rank() === $this->square::SIZE['ranks'];
        if ($this->turn === Color::W) {
            return $wWins xor $bWins;
        }

        return false;
    }

    public function doesDraw(): bool
    {
        $wKing = $this->piece(Color::W, Piece::K);
        $bKing = $this->piece(Color::B, Piece::K);
        if ($this->turn === Color::W) {
            return $wKing->rank() === $this->square::SIZE['ranks'] &&
                $bKing->rank() === $this->square::SIZE['ranks'];
        }

        return false;
    }
}
