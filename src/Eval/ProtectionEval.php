<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Protection evaluation.
 *
 * Total piece value obtained from the squares that are not being defended.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class ProtectionEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Protection';

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->range = [1, 4];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight protection advantage",
            "has a moderate protection advantage",
            "has a decisive protection advantage",
        ];

        foreach ($this->board->getPieces() as $piece) {
            foreach ($piece->attackedPieces() as $attackedPiece) {
                if ($attackedPiece->getId() !== Piece::K) {
                    if (empty($attackedPiece->defendingPieces())) {
                        $this->result[$attackedPiece->oppColor()] += self::$value[$attackedPiece->getId()];
                        $this->elaborate($attackedPiece);
                    }
                }
            }
        }

        $this->explain($this->result);
    }

    /**
     * Elaborate on the result.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     */
    private function elaborate(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);
        $phrase = ucfirst("$phrase is unprotected.");
        if (!in_array($phrase, $this->explanation)) {
            $this->elaboration[] = $phrase;
        }

        $this->elaboration = array_unique($this->elaboration);
    }
}
