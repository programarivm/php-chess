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

class CompleteFunction extends FastFunction
{
    public array $eval = [
        MaterialEval::class => null,
        CenterEval::class => null,
        ConnectivityEval::class => null,
        SpaceEval::class => null,
        PressureEval::class => null,
        KingSafetyEval::class => PressureEval::NAME,
        ProtectionEval::class => null,
        DiscoveredCheckEval::class => null,
        DoubledPawnEval::class => null,
        PassedPawnEval::class => null,
        AdvancedPawnEval::class => null,
        FarAdvancedPawnEval::class => null,
        IsolatedPawnEval::class => null,
        BackwardPawnEval::class => IsolatedPawnEval::NAME,
        DefenseEval::class => ProtectionEval::NAME,
        AbsoluteSkewerEval::class => null,
        AbsolutePinEval::class => null,
        RelativePinEval::class => PressureEval::NAME,
        AbsoluteForkEval::class => null,
        RelativeForkEval::class => null,
        SqOutpostEval::class => null,
        KnightOutpostEval::class => SqOutpostEval::NAME,
        BishopOutpostEval::class => SqOutpostEval::NAME,
        BishopPairEval::class => null,
        BadBishopEval::class => BishopPairEval::NAME,
        DiagonalOppositionEval::class => null,
        DirectOppositionEval::class => null,
        OverloadingEval::class => null,
        BackRankThreatEval::class => null,
        FlightSquareEval::class => null,
        AttackEval::class => null,
        CheckabilityEval::class => null,
    ];
}
