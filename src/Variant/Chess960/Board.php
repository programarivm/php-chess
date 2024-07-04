<?php

namespace Chess\Variant\Chess960;

use Chess\Piece\VariantType;
use Chess\Variant\AbstractBoard;
use Chess\Variant\RandomBoardInterface;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Chess960\StartPieces;
use Chess\Variant\Chess960\Rule\CastlingRule;

class Board extends AbstractBoard implements RandomBoardInterface
{
    const VARIANT = '960';

    private array $startPos;

    public function __construct(
        array $startPos = null,
        array $pieces = null,
        string $castlingAbility = '-'
    ) {
        $this->color = new Color();
        $this->startPos = $startPos ?? (new StartPosition())->getDefault();
        $this->castlingRule = new CastlingRule($this->startPos);
        $this->square = new Square();
        $this->move = new Move();
        $this->variant = VariantType::CLASSICAL;
        if (!$pieces) {
            $pieces = (new StartPieces($this->startPos))->create();
            $this->castlingAbility = CastlingRule::START;
        } else {
            $this->castlingAbility = $castlingAbility;
        }
        foreach ($pieces as $piece) {
            $this->attach($piece);
        }

        $this->refresh();

        $this->startFen = $this->toFen();
    }

    public function getStartPos(): array
    {
        return $this->startPos;
    }
}
