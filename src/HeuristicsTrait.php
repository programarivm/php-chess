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
use Chess\Eval\DirectOppositionEval;

/**
 * HeuristicsTrait
 *
 * A chess game can be thought of in terms of snapshots describing what's going
 * on the board as reported by a number of evaluation features.
 */
trait HeuristicsTrait
{
    /**
     * The evaluation features that make up a heuristic.
     *
     * The sum of the weights equals to 100 as per a multiple-criteria decision
     * analysis (MCDA) based on the point allocation method. This allows to label
     * input vectors for further machine learning purposes.
     *
     * The order in which the different chess evaluations are arranged doesn't
     * really matter. The weights point out a restricted permutation.
     *
     * @var array
     */
    protected $eval = [
        MaterialEval::class => 16,
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
        DirectOppositionEval::class => 4,
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
     * Returns the weighted evaluations.
     *
     * @return array
     */
    public function getEval(): array
    {
        return $this->eval;
    }

    /**
     * Returns the evaluation names.
     *
     * @return array
     */
    public function getEvalNames(): array
    {
        $evalNames = [];
        foreach ($this->eval as $key => $val) {
            $evalNames[] = (new \ReflectionClass($key))->getConstant('NAME');
        }

        return $evalNames;
    }

    /**
     * Sets the evaluations.
     *
     * @param array $eval
     * @return self
     */
    public function setEval(array $eval)
    {
        $this->eval = $eval;

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
}
