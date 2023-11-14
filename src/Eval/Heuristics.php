<?php

namespace Chess\Eval;

class Heuristics
{
    protected $eval = [
        MaterialEval::NAME => 16,
        CenterEval::NAME => 4,
        ConnectivityEval::NAME => 4,
        SpaceEval::NAME => 4,
        PressureEval::NAME => 4,
        KingSafetyEval::NAME => 4,
        TacticsEval::NAME => 4,
        AttackEval::NAME => 4,
        DoubledPawnEval::NAME => 4,
        PassedPawnEval::NAME => 4,
        IsolatedPawnEval::NAME => 4,
        BackwardPawnEval::NAME => 4,
        AbsolutePinEval::NAME => 4,
        RelativePinEval::NAME => 4,
        AbsoluteForkEval::NAME => 4,
        RelativeForkEval::NAME => 4,
        SqOutpostEval::NAME => 4,
        KnightOutpostEval::NAME => 4,
        BishopOutpostEval::NAME => 4,
        BishopPairEval::NAME => 4,
        BadBishopEval::NAME => 4,
        DirectOppositionEval::NAME => 4,
    ];

    public function getEval(): array
    {
        return $this->eval;
    }

    public function getNames(): array
    {
        return array_keys($this->eval);
    }

    public function getWeights(): array
    {
        return array_values($this->eval);
    }
}
