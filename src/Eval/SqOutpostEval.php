<?php

namespace Chess\Eval;

use Chess\Piece\P;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Board;

/**
 * SqOutpostEval
 *
 * @link https://en.wikipedia.org/wiki/Outpost_(chess)
 */
class SqOutpostEval extends AbstractEval
{
    const NAME = 'Square outpost';

    private array $ranks = [3, 4, 5, 6];

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::P) {
                $captureSquares = $piece->getCaptureSqs();
                if ($piece->getColor() === Color::W) {
                    $lFile = chr(ord($piece->getSqFile()) - 2);
                    $rFile = chr(ord($piece->getSqFile()) + 2);
                } else {
                    $lFile = chr(ord($piece->getSqFile()) + 2);
                    $rFile = chr(ord($piece->getSqFile()) - 2);
                    rsort($captureSquares);
                }
                if (in_array($piece->getSq()[1], $this->ranks)) {
                    if (!$this->opposition($piece, $piece->getSqFile())) {
                        if ($lFile >= 'a' && $lFile <= 'h' && !$this->opposition($piece, $lFile)) {
                            $this->result[$piece->getColor()][] = $captureSquares[0];
                            $this->explain([$captureSquares[0]]);
                        }
                        if ($rFile >= 'a' && $rFile <= 'h' &&
                            !$this->opposition($piece, $rFile)
                        ) {
                            $this->result[$piece->getColor()][] = $captureSquares[0];
                            empty($captureSquares[1]) ?: $this->result[$piece->getColor()][] = $captureSquares[1];
                            $this->explain($captureSquares);
                        }
                    }
                }
            }
        }

        $this->result[Color::W] = array_unique($this->result[Color::W]);
        $this->result[Color::B] = array_unique($this->result[Color::B]);

        sort($this->result[Color::W]);
        sort($this->result[Color::B]);
    }

    protected function opposition(P $pawn, string $file): bool
    {
        for ($i = 2; $i < 8; $i++) {
            if ($piece = $this->board->getPieceBySq($file.$i)) {
                if ($piece->getId() === Piece::P) {
                    if ($pawn->getColor() === Color::W) {
                        if ($pawn->getSq()[1] + 2 <= $piece->getSq()[1]) {
                            return true;
                        }
                    } else {
                        if ($pawn->getSq()[1] - 2 >= $piece->getSq()[1]) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    private function explain($sqs): void
    {
        foreach ($sqs as $sq) {
            $sentence = "{$sq} is an outpost.";
            if (!in_array($sentence, $this->phrases)) {
                $this->phrases[] = "{$sq} is an outpost.";
            }
        }
    }
}
