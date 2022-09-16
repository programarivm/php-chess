<?php

namespace Chess\Variant\Chess960;

use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;
use Chess\Piece\RType;
use Chess\Variant\Chess960\StartPosition;

class StartPieces
{
    private array $startPosition;

    private array $startPieces = [];

    public function __construct()
    {
        $this->startPosition = (new StartPosition())->create();
    }

    public function create()
    {
        $longCastlingRook = null;
        foreach ($this->startPosition as $key => $val) {
            $wSq = chr(97+$key).'1';
            $bSq = chr(97+$key).'8';
            $className = "\\Chess\\Piece\\{$val}";
            if ($val !== Piece::R) {
                $this->startPieces[] =  new $className(Color::W, $wSq);
                $this->startPieces[] =  new $className(Color::B, $bSq);
            } elseif (!$longCastlingRook) {
                $this->startPieces[] =  new $className(Color::W, $wSq, RType::CASTLE_LONG);
                $this->startPieces[] =  new $className(Color::B, $bSq, RType::CASTLE_LONG);
                $longCastlingRook = $this->startPosition[$key];
            } else {
                $this->startPieces[] =  new $className(Color::W, $wSq, RType::CASTLE_SHORT);
                $this->startPieces[] =  new $className(Color::B, $bSq, RType::CASTLE_SHORT);
            }
        }

        return $this->startPieces;
    }
}
