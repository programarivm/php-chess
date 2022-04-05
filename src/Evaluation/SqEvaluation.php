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
class SqEvaluation extends AbstractEvaluation
{
    const NAME           = 'square';

    const TYPE_FREE      = 'free';
    const TYPE_USED      = 'used';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];
    }

    public function eval($feature): array
    {
        $pieces = iterator_to_array($this->board, false);
        switch ($feature) {
            case self::TYPE_FREE:
                $this->result = $this->free($pieces);
                break;
            case self::TYPE_USED:
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
            $used[$piece->getColor()][] = $piece->getSquare();
        }

        return $used;
    }
}
