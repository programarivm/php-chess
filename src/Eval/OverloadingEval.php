<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Color;

class OverloadingEval extends AbstractEval implements ExplainEvalInterface
{
    use ExplainEvalTrait;

    const NAME = 'Overloading';

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1, 5];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has an overloaded piece",
            "has multiple overloaded pieces",
        ];

        $this->evaluate(Color::W);
        $this->evaluate(Color::B);
        $this->explain($this->result);
    }

    private function evaluate(string $color): void
    {
        foreach ($this->board->pieces($color) as $piece) {
            if ($this->isPieceOverloaded($piece)) {
                $this->result[$color] += 1;
            }
        }
    }

    /**
     * @param AbstractPiece $piece
     * @return bool
     * A piece is considered overloaded if it is defending more than one square and at least one of those squares
     * is under attack by an opponent's piece.
     */
    private function isPieceOverloaded(AbstractPiece $piece):bool
    {
        $defendedSquares = $piece->defendedSqs();
        $opponentPieces = $this->board->pieces($piece->oppColor());
        $oppMoves = [];
        foreach ($opponentPieces as $oppPiece) {
            if(is_array($oppPiece->moveSqs()) && !empty($oppPiece->moveSqs())){
                $oppMoves = array_merge($oppMoves, $oppPiece->moveSqs());
            }
        }
        if(count($defendedSquares) > 1 && in_array($piece->sq,$oppMoves)){
            return true;
        }
        return false;
    }
}
