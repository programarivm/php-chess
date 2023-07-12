<?php

namespace Chess\Movetext;

use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Termination;

/**
 * Recursive Annotation Variation.
 *
 * @license GPL
 */
class RavMovetext extends AbstractMovetext
{
    /**
     * SAN movetext.
     *
     * @var \Chess\Movetext\SanMovetext
     */
    private SanMovetext $sanMovetext;

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\PGN\Move $move
     * @param string $movetext
     */
    public function __construct(Move $move, string $movetext)
    {
        parent::__construct($move, $movetext);

        $this->sanMovetext = new SanMovetext($move, $movetext);
    }

    /**
     * Before inserting elements into the array of moves.
     *
     * @return \Chess\Movetext\RavMovetext
     */
    protected function beforeInsert(): RavMovetext
    {
        // remove comments
        $str = preg_replace('(\{.*?\})', '', $this->filtered());
        // remove parentheses
        $str = preg_replace('/\(/', '', $str);
        $str = preg_replace('/\)/', '', $str);
        // replace multiple spaces with a single space
        $str = preg_replace('/\s+/', ' ', $str);

        $this->validated = trim($str);

        return $this;
    }

    /**
     * Insert elements into the array of moves.
     *
     * @see \Chess\Play\RavPlay
     */
    protected function insert(): void
    {
        foreach (explode(' ', $this->validated) as $key => $val) {
            if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $val)) {
                $exploded = explode(Move::ELLIPSIS, $val);
                $this->moves[] = $exploded[1];
            } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                $this->moves[] = explode('.', $val)[1];
            } else {
                $this->moves[] = $val;
            }
        }

        $this->moves = array_values(array_filter($this->moves));
    }

    /**
     * Syntactically validated movetext.
     *
     * The syntactically validated movetext does not contain any comments or
     * parentheses.
     *
     * @throws \Chess\Exception\UnknownNotationException
     * @return string
     */
    public function validate(): string
    {
        foreach ($this->moves as $move) {
            $this->move->validate($move);
        }

        return $this->validated;
    }

    /**
     * Filtered movetext.
     *
     * The filtered movetext contains comments by default.
     *
     * @param bool $comments
     * @return string
     */
    public function filtered($comments = true): string
    {
        // remove PGN symbols
        $str = str_replace(Termination::values(), '', $this->movetext);
        // replace FIDE notation with PGN notation
        $str = str_replace('0-0', 'O-O', $str);
        $str = str_replace('0-0-0', 'O-O-O', $str);
        // replace multiple spaces with a single space
        $str = preg_replace('/\s+/', ' ', $str);
        // remove space between dots
        $str = preg_replace('/\s\./', '.', $str);
        // remove space after dots only in the text outside brackets
        preg_match_all('/[^{}]*(?=(?:[^}]*{[^{]*})*[^{}]*$)/', $str, $matches);
        foreach (array_filter($matches[0]) as $match) {
            $replaced = preg_replace('/\.\s/', '.', $match);
            $str = str_replace($match, $replaced, $str);
        }
        if (!$comments) {
            // remove comments
            $str = preg_replace('(\{.*?\})', '', $this->filtered());
            // replace multiple spaces with a single space
            $str = preg_replace('/\s+/', ' ', $str);
        }
        // remove the blank space before and after parentheses
        $str = preg_replace('/\( /', '(', $str);
        $str = preg_replace('/ \)/', ')', $str);

        return trim($str);
    }

    /**
     * Returns the main variation.
     *
     * The main variation does not contain any comments or parentheses.
     *
     * @return string
     */
    public function main(): string
    {
        // remove variations
        $str = preg_replace('/\(([^()]|(?R))*\)/', '', $this->sanMovetext->filtered());
        // remove comments
        $str = preg_replace('(\{.*?\})', '', $str);
        // remove ellipsis
        $str = preg_replace('/[1-9][0-9]*\.\.\./', '', $str);
        // replace multiple spaces with a single space
        $str = preg_replace('/\s+/', ' ', $str);

        return $str;
    }
}
