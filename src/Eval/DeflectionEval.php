<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

class DeflectionEval extends AbstractEval implements 
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Deflection';

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        
    }

    // private function elaborate(AbstractPiece $attacking, AbstractPiece $attacked): void
    // {
    //     $attacking = PiecePhrase::create($attacking);
    //     $attacked = PiecePhrase::create($attacked);

    //     $this->elaboration[] = ucfirst("when $attacked will be moved, a piece that is more valuable than $attacking TODO.");
    // }
}
