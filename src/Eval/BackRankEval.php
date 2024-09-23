<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class BackRankEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface,
    InverseEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'BackRank';

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        $this->range = [1, 4];

        $this->subject = [
            'Black',
            'White',
        ];

        $this->observation = [
            "has a slight back-rank vulnerability",
            "has a moderate back-rank vulnerability",
            "has a severe back-rank vulnerability",
        ];

        $kingPieces = array_filter($this->board->pieces(), function (AbstractPiece $piece) {
            return $piece->isKing();
        });

        foreach ($kingPieces as $piece) {
            $color = $piece->color;
            $backRankSquares = $this->getBackRankSquares($color);
            $isVulnerable = $this->isBackRankVulnerable($piece, $backRankSquares);

            if ($isVulnerable) {
                $this->result[$color][] = $piece->sq;
                $this->elaborate($piece);
            }
        }
        $this->explain([
            Color::W => count($this->result[Color::W]),
            Color::B => count($this->result[Color::B]),
        ]);
    }

    private function getBackRankSquares(string $color): array
    {
        return $color === Color::W
            ? array_map(fn($file) => $file . '1', range('a', 'h'))
            : array_map(fn($file) => $file . '8', range('a', 'h'));    }

    private function isBackRankVulnerable(AbstractPiece $king, array $backRankSquares): bool
    {
        //king should be on the back rank
        if (!in_array($king->sq, $backRankSquares)) {
            return false;
        }

        if (!empty($king->attacking()) && count($king->moveSqs()) < 2) {
            return true;
        }
        return false;
    }

    private function elaborate(AbstractPiece $king): void
    {
        $phrase = PiecePhrase::create($king);
        $this->elaboration[] = ucfirst("$phrase is vulnerable to back-rank checkmate.");
    }
}
