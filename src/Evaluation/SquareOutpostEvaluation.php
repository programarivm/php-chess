<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Piece\Pawn;
use Chess\PGN\Symbol;

class SquareOutpostEvaluation extends AbstractEvaluation
{
    const NAME = 'square_outpost';

    protected $positions = [
        Symbol::WHITE => [],
        Symbol::BLACK => [],
    ];

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->positions();

        $this->result = [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];
    }

    protected function positions()
    {
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getIdentity() === Symbol::PAWN) {
                $this->positions[$piece->getColor()][] = $piece->getPosition();
            }
        }

        return $this;
    }

    public function evaluate(): array
    {
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getIdentity() === Symbol::PAWN) {
                $captureSquares = $piece->getCaptureSquares();
                $lFile = $rFile = '';
                if ($piece->getColor() === Symbol::WHITE) {
                    $lFile = chr(ord($piece->getFile()) - 2);
                    $rFile = chr(ord($piece->getFile()) + 2);
                } else {
                    $lFile = chr(ord($piece->getFile()) + 2);
                    $rFile = chr(ord($piece->getFile()) - 2);
                    rsort($captureSquares);
                }
                !$this->opposition($piece, $lFile)
                    ? $this->results[$piece->getColor()][] = $captureSquares[0]
                    : null;
                !$this->opposition($piece, $rFile)
                    ? $this->results[$piece->getColor()][] = $captureSquares[1]
                    : null;
            }
        }

        return $this;
    }

    protected function opposition(Pawn $pawn, $file)
    {
        for ($i = 1; $i < 9; $i++) {
            if ($piece = $this->board->getPieceByPosition($file.$i)) {
                if ($piece->getIdentity() === Symbol::PAWN) {
                    if ($piece->getColor() === Symbol::WHITE) {
                        if ($pawn->getRank() >= $piece->getRank() - 1) {
                            return true;
                        }
                    } else {
                        if ($pawn->getRank() <= $piece->getRank() + 1) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}
