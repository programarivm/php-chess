<?php

namespace Chess;

use Chess\Eval\AttackEval;
use Chess\Eval\BackwardPawnEval;
use Chess\Eval\CenterEval;
use Chess\Eval\ConnectivityEval;
use Chess\Eval\IsolatedPawnEval;
use Chess\Eval\KingSafetyEval;
use Chess\Eval\MaterialEval;
use Chess\Eval\PressureEval;
use Chess\Eval\SpaceEval;
use Chess\Eval\TacticsEval;
use Chess\Eval\DoubledPawnEval;
use Chess\Eval\PassedPawnEval;
use Chess\Eval\InverseEvalInterface;
use Chess\Eval\AbsolutePinEval;
use Chess\Eval\RelativePinEval;
use Chess\Eval\AbsoluteForkEval;
use Chess\Eval\RelativeForkEval;
use Chess\Eval\SqOutpostEval;
use Chess\Eval\KnightOutpostEval;
use Chess\Eval\BishopOutpostEval;
use Chess\Eval\BishopPairEval;
use Chess\Eval\BadBishopEval;

/**
 * HeuristicsTrait
 *
 * A chess game can be thought of in terms of snapshots describing what's going on
 * the board as reported by a number of evaluation features. Thus, it can be plotted
 * in terms of balance. +1 is the best possible evaluation for White and -1 the
 * best possible evaluation for Black. Both forces being set to 0 means they're
 * actually offset and, therefore, balanced.
 */
trait HeuristicsTrait
{
    /**
     * The evaluation features that make up a heuristic picture.
     *
     * The sum of the weights equals to 100 as per a multiple-criteria decision analysis
     * (MCDA) based on the point allocation method. This allows to label input vectors
     * for further machine learning purposes.
     *
     * The order in which the different chess evaluation features are arranged as
     * a dimension doesn't really matter. The first permutation is used to somehow
     * highlight that a particular dimension is a restricted permutation actually.
     *
     * Let the grandmasters label chess positions. Once a particular position is
     * successfully transformed into an input vector of numbers, then it can be
     * labeled on the assumption that the best possible move that could be made
     * was made — by a chess grandmaster.
     *
     * @var array
     */
    protected $dims = [
        MaterialEval::class => 20,
        CenterEval::class => 4,
        ConnectivityEval::class => 4,
        SpaceEval::class => 4,
        PressureEval::class => 4,
        KingSafetyEval::class => 4,
        TacticsEval::class => 4,
        AttackEval::class => 4,
        DoubledPawnEval::class => 4,
        PassedPawnEval::class => 4,
        IsolatedPawnEval::class => 4,
        BackwardPawnEval::class => 4,
        AbsolutePinEval::class => 4,
        RelativePinEval::class => 4,
        AbsoluteForkEval::class => 4,
        RelativeForkEval::class => 4,
        SqOutpostEval::class => 4,
        KnightOutpostEval::class => 4,
        BishopOutpostEval::class => 4,
        BishopPairEval::class => 4,
        BadBishopEval::class => 4,
    ];

    /**
     * The heuristics of $this->board.
     *
     * @var array
     */
    protected array $result;

    /**
     * The balanced heuristic picture of $this->board.
     *
     * @var array
     */
    protected array $balance;

    /**
     * Returns the weighted dimensions.
     *
     * @return array
     */
    public function getDims(): array
    {
        return $this->dims;
    }

    /**
     * Sets the dimensions.
     *
     * @param array $dims
     * @return \Chess\Heuristics
     */
    public function setDims(array $dims)
    {
        $this->dims = $dims;

        return $this;
    }

    /**
     * Returns the heuristics.
     *
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * Returns the balanced heuristics.
     *
     * A chess game can be plotted in terms of balance. +1 is the best possible
     * evaluation for White and -1 the best possible evaluation for Black. Both
     * forces being set to 0 means they're actually offset and, therefore, balanced.
     *
     * @return array
     */
    public function getBalance(): array
    {
        return $this->balance;
    }

    /**
     * Returns the resized balanced heuristics given a new range of values.
     *
     * A balanced, chess heuristic is within a range between -1 and 1 by default;
     * however, [0, 1] may be more convenient to label input vectors in certain
     * situations; for example, when using a geometric sum. That way the sum
     * won't be neutralized because of subtractions taking place.
     *
     * @param float $newMin
     * @param float $newMax
     * @return array
     */
    abstract public function getResizedBalance(float $newMin, float $newMax): array;
}
