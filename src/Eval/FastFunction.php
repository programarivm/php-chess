<?php

namespace Chess\Eval;

class FastFunction extends AbstractFunction
{
    public static array $eval = [
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
        OverloadingEval::class,
        BackRankThreatEval::class,
        FlightSquareEval::class,
    ];
}
