<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;

/**
 * Square evaluation.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class SquareEvaluation extends AbstractEvaluation
{
    const NAME              = 'square';

    const FEATURE_FREE      = 'free';
    const FEATURE_USED      = 'used';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];
    }

    public function evaluate($feature): array
    {
        $pieces = iterator_to_array($this->board, false);
        switch ($feature) {
            case self::FEATURE_FREE:
                $this->result = $this->free($pieces);
                break;
            case self::FEATURE_USED:
                $this->result = $this->used($pieces);
                break;
        }

        return $this->result;
    }

    /**
     * All squares.
     *
     * @return array
     */
    private function all(): array
    {
        $all = [];
        for($i=0; $i<8; $i++) {
            for($j=1; $j<=8; $j++) {
                $all[] = chr((ord('a') + $i)) . $j;
            }
        }

        return $all;
    }

    /**
     * Free squares.
     *
     * @return array
     */
    private function free(array $pieces): array
    {
        $used = $this->used($pieces);

        return array_values(
            array_diff(
                $this->all(),
                array_merge($used[Symbol::WHITE], $used[Symbol::BLACK])
        ));
    }

    /**
     * Squares used by both players.
     *
     * @return array
     */
    private function used(array $pieces): array
    {
        $used = [
            Symbol::WHITE => [],
            Symbol::BLACK => []
        ];

        foreach ($pieces as $piece) {
            $used[$piece->getColor()][] = $piece->getPosition();
        }

        return $used;
    }
}
