<?php

namespace Chess\Function;

use Chess\Eval\AbsoluteForkEval;
use Chess\Eval\AbsolutePinEval;
use Chess\Eval\AbsoluteSkewerEval;
use Chess\Eval\AdvancedPawnEval;
use Chess\Eval\AttackEval;
use Chess\Eval\BackwardPawnEval;
use Chess\Eval\BadBishopEval;
use Chess\Eval\BishopOutpostEval;
use Chess\Eval\BishopPairEval;
use Chess\Eval\CenterEval;
use Chess\Eval\CheckmateInOneEval;
use Chess\Eval\CheckmateInPlyEval;
use Chess\Eval\ConnectivityEval;
use Chess\Eval\DefenseEval;
use Chess\Eval\DiagonalOppositionEval;
use Chess\Eval\DirectOppositionEval;
use Chess\Eval\DiscoveredCheckEval;
use Chess\Eval\DoubledPawnEval;
use Chess\Eval\FarAdvancedPawnEval;
use Chess\Eval\InverseEvalInterface;
use Chess\Eval\IsolatedPawnEval;
use Chess\Eval\KingSafetyEval;
use Chess\Eval\KnightOutpostEval;
use Chess\Eval\MaterialEval;
use Chess\Eval\PassedPawnEval;
use Chess\Eval\PressureEval;
use Chess\Eval\ProtectionEval;
use Chess\Eval\RelativeForkEval;
use Chess\Eval\RelativePinEval;
use Chess\Eval\SpaceEval;
use Chess\Eval\SqOutpostEval;

/**
 * QuadraticComplexityFunction
 *
 * Quadratic evaluation function.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class QuadraticComplexityFunction extends AbstractFunction
{
    const NAME = 'Quadratic';

    /**
     * The evaluation features.
     *
     * @var array
     */
    protected array $eval = [
        MaterialEval::class => 13,
        CenterEval::class => 3,
        ConnectivityEval::class => 3,
        SpaceEval::class => 3,
        PressureEval::class => 3,
        KingSafetyEval::class => 3,
        ProtectionEval::class => 3,
        AttackEval::class => 3,
        DiscoveredCheckEval::class => 3,
        DoubledPawnEval::class => 3,
        PassedPawnEval::class => 3,
        AdvancedPawnEval::class => 3,
        FarAdvancedPawnEval::class => 3,
        IsolatedPawnEval::class => 3,
        BackwardPawnEval::class => 3,
        DefenseEval::class => 3,
        AbsoluteSkewerEval::class => 3,
        AbsolutePinEval::class => 3,
        RelativePinEval::class => 3,
        AbsoluteForkEval::class => 3,
        RelativeForkEval::class => 3,
        SqOutpostEval::class => 3,
        KnightOutpostEval::class => 3,
        BishopOutpostEval::class => 3,
        BishopPairEval::class => 3,
        BadBishopEval::class => 3,
        DiagonalOppositionEval::class => 3,
        DirectOppositionEval::class => 3,
        CheckmateInPlyEval::class => 3,
        CheckmateInOneEval::class => 3,
    ];
}
