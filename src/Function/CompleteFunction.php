<?php

namespace Chess\Function;

use Chess\Eval\AbsoluteForkEval;
use Chess\Eval\AbsolutePinEval;
use Chess\Eval\AbsoluteSkewerEval;
use Chess\Eval\AdvancedPawnEval;
use Chess\Eval\AttackEval;
use Chess\Eval\BackRankThreatEval;
use Chess\Eval\BackwardPawnEval;
use Chess\Eval\BadBishopEval;
use Chess\Eval\BishopOutpostEval;
use Chess\Eval\BishopPairEval;
use Chess\Eval\CenterEval;
use Chess\Eval\CheckabilityEval;
use Chess\Eval\ConnectivityEval;
use Chess\Eval\DefenseEval;
use Chess\Eval\DiagonalOppositionEval;
use Chess\Eval\DirectOppositionEval;
use Chess\Eval\DiscoveredCheckEval;
use Chess\Eval\DoubledPawnEval;
use Chess\Eval\FarAdvancedPawnEval;
use Chess\Eval\FlightSquareEval;
use Chess\Eval\InverseEvalInterface;
use Chess\Eval\IsolatedPawnEval;
use Chess\Eval\KingSafetyEval;
use Chess\Eval\KnightOutpostEval;
use Chess\Eval\MaterialEval;
use Chess\Eval\OverloadingEval;
use Chess\Eval\PassedPawnEval;
use Chess\Eval\PressureEval;
use Chess\Eval\ProtectionEval;
use Chess\Eval\RelativeForkEval;
use Chess\Eval\RelativePinEval;
use Chess\Eval\SpaceEval;
use Chess\Eval\SqOutpostEval;

class CompleteFunction extends AbstractFunction
{
    protected array $eval = [
        MaterialEval::class,
        CenterEval::class,
        ConnectivityEval::class,
        SpaceEval::class,
        PressureEval::class,
        KingSafetyEval::class,
        ProtectionEval::class,
        DiscoveredCheckEval::class,
        DoubledPawnEval::class,
        PassedPawnEval::class,
        AdvancedPawnEval::class,
        FarAdvancedPawnEval::class,
        IsolatedPawnEval::class,
        BackwardPawnEval::class,
        DefenseEval::class,
        AbsoluteSkewerEval::class,
        AbsolutePinEval::class,
        RelativePinEval::class,
        AbsoluteForkEval::class,
        RelativeForkEval::class,
        SqOutpostEval::class,
        KnightOutpostEval::class,
        BishopOutpostEval::class,
        BishopPairEval::class,
        BadBishopEval::class,
        DiagonalOppositionEval::class,
        DirectOppositionEval::class,
        AttackEval::class,
        OverloadingEval::class,
        BackRankThreatEval::class,
        CheckabilityEval::class,
        FlightSquareEval::class,
    ];
}
