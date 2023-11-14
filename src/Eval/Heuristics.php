<?php

namespace Chess\Eval;

class Heuristics
{
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

    public function getEval(): array
    {
        return $this->eval;
    }

    public function getNames(): array
    {
        $evalNames = [];
        foreach ($this->eval as $key => $val) {
            $evalNames[] = (new \ReflectionClass($key))->getConstant('NAME');
        }

        return $evalNames;
    }
}
