<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

class RelativeForkEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Relative fork';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->range = [1, 9];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight relative fork advantage",
            "has a moderate relative fork advantage",
            "has a total relative fork advantage",
        ];

        foreach ($this->board->getPieces() as $piece) {
            if (!$piece->isAttackingKing()) {
                $attackedPieces = $piece->attackedPieces();
                if (count($attackedPieces) >= 2) {
                    $pieceValue = self::$value[$piece->id];
                    foreach ($attackedPieces as $attackedPiece) {
                        $attackedPieceValue = self::$value[$attackedPiece->id];
                        if ($pieceValue < $attackedPieceValue) {
                            $this->result[$piece->color] += $attackedPieceValue;
                            $this->elaborate($attackedPiece);
                        }
                    }
                }
            }
        }

        $this->explain($this->result);
    }

    private function elaborate(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);

        $this->elaboration[] = "Relative fork attack on {$phrase}.";
    }
}
