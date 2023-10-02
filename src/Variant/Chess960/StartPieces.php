<?php

namespace Chess\Variant\Chess960;

use Chess\Piece\P;
use Chess\Piece\RType;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Chess960\StartPosition;

class StartPieces
{
    private array $startPos;

    private array $startPieces;

    private array $size;

    public function __construct(array $startPos)
    {
        $this->startPos = $startPos;
        $this->startPieces = [];
        $this->size = Square::SIZE;
    }

    public function create()
    {
        $longCastlingRook = null;
        foreach ($this->startPos as $key => $val) {
            $wSq = chr(97+$key) . '1';
            $bSq = chr(97+$key) . $this->size['files'];
            $className = "\\Chess\\Piece\\{$val}";
            if ($val === Piece::K) {
                $this->startPieces[] =  new $className(Color::W, $wSq, $this->size);
                $this->startPieces[] =  new $className(Color::B, $bSq, $this->size);
            } elseif ($val !== Piece::R) {
                $this->startPieces[] =  new $className(Color::W, $wSq, $this->size);
                $this->startPieces[] =  new $className(Color::B, $bSq, $this->size);
            } elseif (!$longCastlingRook) {
                $this->startPieces[] =  new $className(Color::W, $wSq, $this->size, RType::CASTLE_LONG);
                $this->startPieces[] =  new $className(Color::B, $bSq, $this->size, RType::CASTLE_LONG);
                $longCastlingRook = $this->startPos[$key];
            } else {
                $this->startPieces[] =  new $className(Color::W, $wSq, $this->size, RType::CASTLE_SHORT);
                $this->startPieces[] =  new $className(Color::B, $bSq, $this->size, RType::CASTLE_SHORT);
            }
        }
        $this->startPieces[] = new P(Color::W, 'a2', $this->size);
        $this->startPieces[] = new P(Color::W, 'b2', $this->size);
        $this->startPieces[] = new P(Color::W, 'c2', $this->size);
        $this->startPieces[] = new P(Color::W, 'd2', $this->size);
        $this->startPieces[] = new P(Color::W, 'e2', $this->size);
        $this->startPieces[] = new P(Color::W, 'f2', $this->size);
        $this->startPieces[] = new P(Color::W, 'g2', $this->size);
        $this->startPieces[] = new P(Color::W, 'h2', $this->size);
        $this->startPieces[] = new P(Color::B, 'a7', $this->size);
        $this->startPieces[] = new P(Color::B, 'b7', $this->size);
        $this->startPieces[] = new P(Color::B, 'c7', $this->size);
        $this->startPieces[] = new P(Color::B, 'd7', $this->size);
        $this->startPieces[] = new P(Color::B, 'e7', $this->size);
        $this->startPieces[] = new P(Color::B, 'f7', $this->size);
        $this->startPieces[] = new P(Color::B, 'g7', $this->size);
        $this->startPieces[] = new P(Color::B, 'h7', $this->size);

        return $this->startPieces;
    }
}
