<?php

namespace Chess\Movetext;

use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Standard Algebraic Notation.
 *
 * @license GPL
 */
class SanMovetext extends AbstractMovetext
{
    /**
     * Metadata.
     *
     * @var array
     */
    protected object $metadata;

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\PGN\Move $move
     * @param string $movetext
     */
    public function __construct(Move $move, string $movetext)
    {
        parent::__construct($move, $movetext);

        $this->metadata = (object) [
            'firstMove' => $this->firstMove(),
            'lastMove' => $this->lastMove(),
            'turn' => $this->turn(),
        ];
    }

    /**
     * Returns the metadata.
     *
     * @return object
     */
    public function getMetadata(): object
    {
        return $this->metadata;
    }

    /**
     * Before inserting elements into the array of moves.
     *
     * @return \Chess\Movetext\SanMovetext
     */
    protected function beforeInsert(): SanMovetext
    {
        $str = preg_replace('(\{.*?\})', '', $this->filtered());
        $str = preg_replace('/\s+/', ' ', $str);

        $this->validated = trim($str);

        return $this;
    }

    /**
     * Insert elements into the array of moves for further validation.
     *
     * @see \Chess\Play\SanPlay
     */
    protected function insert(): void
    {
        foreach (explode(' ', $this->validated) as $key => $val) {
            if (!NagMovetext::glyph($val)) {
                if ($key === 0) {
                    if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $val)) {
                        $exploded = explode(Move::ELLIPSIS, $val);
                        $this->moves[] = Move::ELLIPSIS;
                        $this->moves[] = $exploded[1];
                    } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                        $this->moves[] = explode('.', $val)[1];
                    } else {
                        $this->moves[] = $val;
                    }
                } else {
                    if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $val)) {
                        $exploded = explode(Move::ELLIPSIS, $val);
                        $this->moves[] = Move::ELLIPSIS;
                        $this->moves[] = $exploded[1];
                    } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                        $this->moves[] = explode('.', $val)[1];
                    } else {
                        $this->moves[] = $val;
                    }
                }
            }
        }

        $this->moves = array_values(array_filter($this->moves));
    }

    /**
     * Returns the current turn.
     *
     * @return string
     */
    protected function turn(): string
    {
        $exploded = explode(' ', $this->validated);
        $last = end($exploded);
        if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $last)) {
            return Color::W;
        } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $last)) {
            return Color::B;
        }

        return Color::W;
    }

    /**
     * Returns the first move.
     *
     * @return string
     */
    protected function firstMove(): string
    {
        $exploded = explode(' ', $this->validated);

        return $exploded[0];
    }

    /**
     * Returns the last move.
     *
     * @return string
     */
    protected function lastMove(): string
    {
        $exploded = explode(' ', $this->validated);
        $last = end($exploded);
        if (!str_contains($last, '.')) {
            $nextToLast = prev($exploded);
            return "{$nextToLast} {$last}";
        }

        return $last;
    }

    /**
     * Syntactically validated movetext.
     *
     * @throws \Chess\Exception\UnknownNotationException
     * @return string
     */
    public function validate(): string
    {
        foreach ($this->moves as $move) {
            if ($move !== Move::ELLIPSIS) {
                $this->move->validate($move);
            }
        }

        return $this->validated;
    }

    /**
     * Filtered movetext.
     *
     * @param bool $comments
     * @param bool $nags
     * @return string
     */
    public function filtered($comments = true, $nags = true): string
    {
        $str = parent::filtered($comments, $nags);
        $str = preg_replace('/\(([^()]|(?R))*\)/', '', $str);

        return trim($str);
    }

    /**
     * Returns an array representing the movetext as a sequence of moves.
     *
     * e.g. 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+
     *
     * Array
     * (
     *     [0] => 1.d4 Nf6
     *     [1] => 1.d4 Nf6 2.Nf3 e6
     *     [2] => 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+
     * )
     *
     * @return array
     */
    public function sequence(): array
    {
        $n = floor(count($this->moves) / 2);
        $sequence = [];
        for ($i = 0; $i < $n; $i++) {
            $j = 2 * $i;
            if (isset($this->moves[$j+1])) {
                $item = end($sequence) . ' ' .  $i + 1 .
                ".{$this->moves[$j]} {$this->moves[$j+1]}";
                $sequence[] = trim($item);
            }
        }

        return $sequence;
    }
}
