<?php

namespace Chess\Movetext;

use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\Termination;

abstract class AbstractMovetext
{
    public Move $move;

    public string $movetext;

    public array $moves;

    public string $validated;

    public function __construct(Move $move, string $movetext)
    {
        $this->move = $move;
        $this->movetext = str_replace(["\r\n", "\r", "\n"], ' ', $movetext);
        $this->moves = [];

        $this->beforeInsert()->insert();
    }

    public function filtered($comments = true, $nags = true): string
    {
        $str = $this->movetext;
        // the filtered movetext contains comments and NAGs by default
        if (!$comments) {
            // remove comments
            $str = preg_replace('(\{.*?\})', '', $str);
        }
        if (!$nags) {
            // remove nags
            preg_match_all('/\$[1-9][0-9]*/', $str, $matches);
            usort($matches[0], function ($a, $b) {
                return strlen($a) < strlen($b);
            });
            foreach (array_filter($matches[0]) as $match) {
                $str = str_replace($match, '', $str);
            }
        }
        // remove PGN symbols
        $str = str_replace((new Termination())->values(), '', $str);
        // replace FIDE notation with PGN notation
        $str = str_replace('0-0', 'O-O', $str);
        $str = str_replace('0-0-0', 'O-O-O', $str);
        // replace multiple spaces with a single space
        $str = preg_replace('/\s+/', ' ', $str);
        // remove space between dots
        $str = preg_replace('/\s\./', '.', $str);
        // remove space after dots only in the text outside brackets
        preg_match_all('/[^{}]*(?=(?:[^}]*{[^{]*})*[^{}]*$)/', $str, $matches);
        foreach ($matches[0] as $match) {
            $replaced = preg_replace('/\.\s/', '.', $match);
            $str = str_replace($match, $replaced, $str);
        }
        // remove space between number and dot
        $str = preg_replace('/\.\.\.\s/', '...', $str);
        // remove space before and after parentheses
        $str = preg_replace('/\( /', '(', $str);
        $str = preg_replace('/ \)/', ')', $str);
        // remove space before and after curly brackets
        $str = preg_replace('/\{ /', '{', $str);
        $str = preg_replace('/ \}/', '}', $str);

        return trim($str);
    }

    abstract protected function beforeInsert(): AbstractMovetext;

    abstract protected function insert(): void;

    abstract public function validate(): string;
}
