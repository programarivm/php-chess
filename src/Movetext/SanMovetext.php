<?php

namespace Chess\Movetext;

use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\Color;

class SanMovetext extends AbstractMovetext
{
    public array $metadata;

    public function __construct(Move $move, string $movetext)
    {
        parent::__construct($move, $movetext);

        $this->metadata = [
            'firstMove' => $this->firstMove(),
            'lastMove' => $this->lastMove(),
            'turn' => $this->turn(),
        ];
    }

    protected function beforeInsert(): SanMovetext
    {
        $str = preg_replace('(\{.*?\})', '', $this->filtered());
        $str = preg_replace('/\s+/', ' ', $str);

        $this->validated = trim($str);

        return $this;
    }

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

    protected function firstMove(): string
    {
        $exploded = explode(' ', $this->validated);

        return $exploded[0];
    }

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

    public function validate(): string
    {
        foreach ($this->moves as $move) {
            if ($move !== Move::ELLIPSIS) {
                $this->move->validate($move);
            }
        }

        return $this->validated;
    }

    public function filtered($comments = true, $nags = true): string
    {
        $str = parent::filtered($comments, $nags);
        $str = preg_replace('/\(([^()]|(?R))*\)/', '', $str);

        return trim($str);
    }
}
