<?php

namespace Chess\Eval;

/**
 * Unique Imbalance Evaluation
 *
 * Classes implementing this interface are designed so that each move can create
 * a unique sufficiently small imbalance. It can be seen as a correction factor
 * to break the symmetry of the system, it is used to adjust the evaluation as
 * per Blab's theorem:
 *
 * "A chess position can be obtained from the last oscillation of a chessboard."
 *
 * FEN(n) ≡ Ψ(n)
 *
 * Remember, Blab's corollary says that an entire chess game can be obtained
 * from a signal encoding the oscillations of a chessboard.
 */
interface UniqueImbalanceEvalInterface
{
}
