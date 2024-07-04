<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Piece\VariantType;
use Chess\Variant\AbstractBoard;
use Chess\Variant\RandomBoardInterface;
use Chess\Variant\Capablanca\PGN\Move;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\CapablancaFischer\StartPieces;
use Chess\Variant\CapablancaFischer\Rule\CastlingRule;
use Chess\Variant\Classical\PGN\AN\Color;

class Board extends AbstractBoard implements RandomBoardInterface
{
    const VARIANT = 'capablanca-fischer';

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
        $this->variant = VariantType::CAPABLANCA;
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
