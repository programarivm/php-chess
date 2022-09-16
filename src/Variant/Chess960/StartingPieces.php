<?php

namespace Chess\Variant\Chess960;

use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;
use Chess\Piece\RType;
use Chess\Variant\Chess960\StartingPosition;

class StartingPieces
{
    private array $startingPosition;

    private array $startingPieces = [];

    public function __construct()
    {
        $this->startingPosition = (new StartingPosition())->create();
    }

    public function create()
    {
        $longCastlingRook = null;
        foreach ($this->startingPosition as $key => $val) {
            $wSq = chr(97+$key).'1';
            $bSq = chr(97+$key).'8';
            $className = "\\Chess\\Piece\\{$val}";
            if ($val !== Piece::R) {
                $this->startingPieces[] =  new $className(Color::W, $wSq);
                $this->startingPieces[] =  new $className(Color::B, $bSq);
            } elseif (!$longCastlingRook) {
                $this->startingPieces[] =  new $className(Color::W, $wSq, RType::CASTLE_LONG);
                $this->startingPieces[] =  new $className(Color::B, $bSq, RType::CASTLE_LONG);
                $longCastlingRook = $this->startingPosition[$key];
            } else {
                $this->startingPieces[] =  new $className(Color::W, $wSq, RType::CASTLE_SHORT);
                $this->startingPieces[] =  new $className(Color::B, $bSq, RType::CASTLE_SHORT);
            }
        }

        return $this->startingPieces;
    }
}
