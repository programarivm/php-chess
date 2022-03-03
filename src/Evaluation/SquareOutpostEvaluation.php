<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Piece\Pawn;
use Chess\PGN\Symbol;

class SquareOutpostEvaluation extends AbstractEvaluation
{
    const NAME = 'square_outpost';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];
    }

    public function evaluate(): array
    {
        $ranks = [3, 4, 5, 6];
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getIdentity() === Symbol::PAWN) {
                $captureSquares = $piece->getCaptureSquares();
                if ($piece->getColor() === Symbol::WHITE) {
                    $lFile = chr(ord($piece->getFile()) - 2);
                    $rFile = chr(ord($piece->getFile()) + 2);
                } else {
                    $lFile = chr(ord($piece->getFile()) + 2);
                    $rFile = chr(ord($piece->getFile()) - 2);
                    rsort($captureSquares);
                }
                if (in_array($piece->getPosition()[1], $ranks)) {
                    $this->opposition($piece, $lFile) ?: $this->result[$piece->getColor()][] = $captureSquares[0];
                    if (!$this->opposition($piece, $rFile)) {
                        $this->result[$piece->getColor()][] = $captureSquares[0];
                        empty($captureSquares[1]) ?: $this->result[$piece->getColor()][] = $captureSquares[1];
                    }
                }
            }
        }
        $this->result[Symbol::WHITE] = array_unique($this->result[Symbol::WHITE]);
        $this->result[Symbol::BLACK] = array_unique($this->result[Symbol::BLACK]);
        sort($this->result[Symbol::WHITE]);
        sort($this->result[Symbol::BLACK]);

        return $this->result;
    }

    protected function opposition(Pawn $pawn, $file)
    {
        if (!($file >= 'a' && $file <= 'h')) {
            return true;
        }
        for ($i = 2; $i < 8; $i++) {
            if ($piece = $this->board->getPieceByPosition($file.$i)) {
                if ($piece->getIdentity() === Symbol::PAWN) {
                    if ($pawn->getColor() === Symbol::WHITE) {
                        if ($pawn->getPosition()[1] + 2 <= $piece->getPosition()[1]) {
                            return true;
                        }
                    } else {
                        if ($pawn->getPosition()[1] - 2 >= $piece->getPosition()[1]) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}
