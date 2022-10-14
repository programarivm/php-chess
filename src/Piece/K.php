<?php

namespace Chess\Piece;

use Chess\Piece\AbstractPiece;
use Chess\Piece\RType;
use Chess\Variant\Classical\FEN\Field\CastlingAbility;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * King.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class K extends AbstractPiece
{
    /**
     * @var \Chess\Piece\R
     */
    private R $rook;

    /**
     * @var \Chess\Piece\B
     */
    private B $bishop;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     * @param array $size
     */
    public function __construct(string $color, string $sq, array $size)
    {
        parent::__construct($color, $sq, $size, Piece::K);

        $this->rook = new R($color, $sq, $size, RType::SLIDER);
        $this->bishop = new B($color, $sq, $size);

        $this->mobility();
    }

    /**
     * Calculates the piece's mobility.
     *
     * @return \Chess\Piece\AbstractPiece
     */
    protected function mobility(): AbstractPiece
    {
        $mobility =  [
            ... (array) $this->rook->getMobility(),
            ... (array) $this->bishop->getMobility()
        ];

        foreach($mobility as $key => $val) {
            $mobility[$key] = $val[0] ?? null;
        }

        $this->mobility = (object) array_filter(array_unique($mobility));

        return $this;
    }

    /**
     * Returns the piece's legal moves.
     *
     * @return array
     */
    public function sqs(): array
    {
        $sqs = [
            ...$this->sqsKing(),
            ...$this->sqsCaptures(),
            ...[$this->sqCastleLong()],
            ...[$this->sqCastleShort()]
        ];

        return array_values(array_filter($sqs));
    }

    /**
     * Returns the squares defended by the piece.
     *
     * @return array|null
     */
    public function defendedSqs(): ?array
    {
        $sqs = [];
        foreach ($this->mobility as $sq) {
            if (in_array($sq, $this->board->getSqEval()->used->{$this->getColor()})) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }

    public function sqCastleLong(): ?string
    {
        $rule = $this->board->getCastlingRule()[$this->getColor()][Piece::K][Castle::LONG];

        if (CastlingAbility::long($this->board->getCastlingAbility(), $this->getColor())) {
            if (
                !$this->board->isCheck() &&
                !array_diff($rule['sqs'], $this->board->getSqEval()->free) &&
                empty(array_intersect($rule['sqs'], $this->board->getSpaceEval()->{$this->oppColor()}))
            ) {
                return $rule['sq']['next'];
            }
        }

        return null;
    }

    public function sqCastleShort(): ?string
    {
        $rule = $this->board->getCastlingRule()[$this->getColor()][Piece::K][Castle::SHORT];

        if (CastlingAbility::short($this->board->getCastlingAbility(), $this->getColor())) {
            if (
                !$this->board->isCheck() &&
                !array_diff($rule['sqs'], $this->board->getSqEval()->free) &&
                empty(array_intersect($rule['sqs'], $this->board->getSpaceEval()->{$this->oppColor()}))
            ) {
                return $rule['sq']['next'];
            }
        }

        return null;
    }

    protected function sqsCaptures(): ?array
    {
        $sqsCaptures = array_intersect(
            array_values((array)$this->mobility),
            $this->board->getSqEval()->used->{$this->oppColor()}
        );

        return array_diff($sqsCaptures, $this->board->getDefenseEval()->{$this->oppColor()});
    }

    protected function sqsKing(): ?array
    {
        $sqsKing = array_intersect(array_values((array)$this->mobility), $this->board->getSqEval()->free);

        return array_diff($sqsKing, $this->board->getSpaceEval()->{$this->oppColor()});
    }

    /**
     * Returns the castle rook.
     *
     * @param string $type
     * @return R|null \Chess\Piece\R|null
     */
    public function getCastleRook(string $type): ?R
    {
        $rule = $this->board->getCastlingRule()[$this->getColor()][Piece::R][$type];
        if ($type === RType::CASTLE_LONG && $this->sqCastleLong()) {
            if ($piece = $this->board->getPieceBySq($rule['sq']['current'])) {
                if ($piece->getId() === Piece::R) {
                    return $piece;
                }
            }
        } elseif ($type === RType::CASTLE_SHORT && $this->sqCastleShort()) {
            if ($piece = $this->board->getPieceBySq($rule['sq']['current'])) {
                if ($piece->getId() === Piece::R) {
                    return $piece;
                }
            }
        }

        return null;
    }
}
