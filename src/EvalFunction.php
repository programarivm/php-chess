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
 * EvalFunction
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class EvalFunction
{
    /**
     * The evaluation features.
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
     * Returns the evaluation features.
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
    public function names(): array
    {
        foreach ($this->eval as $key => $val) {
            $names[] = (new \ReflectionClass($key))->getConstant('NAME');
        }

        return $names;
    }

    /**
     * Returns the evaluation weights.
     *
     * @return array
     */
    public function weights(): array
    {
        return array_values($this->eval);
    }
}
