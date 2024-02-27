<?php

namespace Chess\UciEngine\Details;

/**
 * UCI Score Structure for handling the analysis score.
 */
class Score
{
    /**
     * The score.
     *
     * @var int
     */
    private int $score;

    /**
     * The type of the score.
     *
     * @var Type
     */
    private Type $type;

    public function __construct(int $score, Type $type = Type::CP)
    {
        $this->score = $score;
        $this->type = $type;
    }

    /**
     * Check if the score is a mate.
     *
     * @return bool
     */
    public function isMate(): bool
    {
        return $this->type === Type::MATE;
    }

    /**
     * Get the score. Always check if the score is a mate!
     *
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }
}
