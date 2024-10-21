<?php

namespace Chess\Movetext;

use Chess\Variant\Classical\PGN\Move;

class RavMovetext extends AbstractMovetext
{
    /**
     * SAN movetext.
     *
     * @var \Chess\Movetext\SanMovetext
     */
    protected SanMovetext $sanMovetext;

    /**
     * RAV breakdown.
     *
     * @var array
     */
    public array $breakdown;

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

        $this->breakdown();
    }

    /**
     * Before inserting elements into the array of moves.
     *
     * @return \Chess\Movetext\RavMovetext
     */
    protected function beforeInsert(): RavMovetext
    {
        $patterns = [
            '(\{.*?\})',
            '/\(/',
            '/\)/',
            '/\s+/',
        ];

        $replacements = [
            '',
            '',
            '',
            ' ',
        ];

        $this->validated = preg_replace($patterns, $replacements, $this->filtered());

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
            if (!NagMovetext::glyph($val)) {
                if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $val)) {
                    $exploded = explode(Move::ELLIPSIS, $val);
                    $this->moves[] = $exploded[1];
                } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                    $this->moves[] = explode('.', $val)[1];
                } else {
                    $this->moves[] = $val;
                }
            }
        }

        $this->moves = array_values(array_filter($this->moves));
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
            $this->move->validate($move);
        }

        return $this->validated;
    }

    /**
     * Returns the main variation.
     *
     * @return string
     */
    public function main(): string
    {
        $patterns = [
            '/\(([^()]|(?R))*\)/',
            '(\{.*?\})',
            '/[1-9][0-9]*\.\.\./',
            '/\s+/',
        ];

        $replacements = [
            '',
            '',
            '',
            ' ',
        ];

        return preg_replace($patterns, $replacements, $this->sanMovetext->filtered());
    }

    /**
     * Finds out if an element is immediately preceding another one.
     *
     * @param SanMovetext $prev
     * @param SanMovetext $curr
     * @return bool
     */
    public function isPrevious(SanMovetext $prev, SanMovetext $curr): bool
    {
        foreach ($this->lines() as $line) {
            foreach ($line as $key => $val) {
                if (str_ends_with(current($val), $prev->metadata['lastMove']) &&
                    str_starts_with(key($val), $curr->metadata['firstMove'])
                ) {
                    return true;
                } elseif (str_contains(key($val), "{$prev->metadata['lastMove']} {$curr->metadata['firstMove']}")) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * A breakdown of the variations for further processing.
     *
     * @return RavMovetext
     */
    protected function breakdown(): RavMovetext
    {
        $str = $this->filtered();
        // escape parentheses enclosed in curly brackets
        preg_match_all('/{(.*?)}/', $str, $matches);
        foreach ($matches[0] as $match) {
            $replaced = str_replace('(', '<--', $match);
            $replaced = str_replace(')', '-->', $replaced);
            $str = str_replace($match, $replaced, $str);
        }
        // split by parentheses outside the curly brackets
        $arr = preg_split("/[()]+/", $str, -1, PREG_SPLIT_NO_EMPTY);
        $arr = array_map('trim', $arr);
        $arr = array_values(array_filter($arr));
        // unescape parentheses enclosed in curly brackets
        foreach ($arr as &$item) {
            $item = str_replace('<--', '(', $item);
            $item = str_replace('-->', ')', $item);
        }

        $this->breakdown = $arr;

        return $this;
    }

    /**
    * Returns the maximum depth of nested parentheses.
    *
    * @return int
    */
    public function maxDepth(): int
    {
        $str = $this->filtered(false, false);
        $count = 0;
        $stack = [];
        for ($i = 0; $i < strlen($str); $i++) {
            if ($str[$i] == '(') {
                array_push($stack, $i);
            } elseif ($str[$i] == ')') {
                if ($count < count($stack)) {
                    $count = count($stack);
                }
                array_pop($stack);
            }
        }

        return $count;
    }

   /**
    * Returns all occurrences enclosed in the innermost parentheses.
    *
    * @return array
    */
    public function maxDepthStrings(): array
    {
        $matches = [];
        $str = $this->filtered(false, false);
        $maxDepth = $this->maxDepth();
        if ($maxDepth === 0) {
            return [
                $str,
            ];
        } else {
            $count = 0;
            for ($i = 0; $i < strlen($str); $i++) {
                if ($str[$i] == ')') {
                    $count -= 1;
                } elseif ($str[$i] == '(') {
                    $count += 1;
                } elseif ($count === $maxDepth) {
                    $substr = substr($str, $i);
                    $match = str_replace('(', '', explode(')', $substr)[0]);
                    $matches[] = [
                        $match => $this->previous($substr),
                    ];
                    $count -= 1;
                    $i += strlen($match);
                }
            }
        }

        return $matches;
    }

    /**
     * Returns all lines sorted by depth level.
     *
     * @return array
     */
    public function lines(): array
    {
        $matches = [];
        $str = $this->filtered(false, false);
        $clone = unserialize(serialize($this));
        $maxDepth = $clone->maxDepth();

        while ($maxDepth > 0) {
            $matches[$maxDepth] = $clone->maxDepthStrings();
            $enclosedMatches = [];
            foreach ($matches[$maxDepth] as $match) {
                $enclosedMatches[] = '(' . key($match) . ')';
            }
            $str = str_replace($enclosedMatches, '', $str);
            $str = preg_replace('/\s+/', ' ', $str);
            $str = preg_replace('/\( /', '(', $str);
            $str = preg_replace('/ \)/', ')', $str);
            $clone = new self($this->move, $str);
            $maxDepth = $clone->maxDepth();
        }

        $mainLine = preg_replace('/\(([^()]|(?R))*\)/', '', $this->sanMovetext->filtered(false, false));
        $mainLine = preg_replace('/\s+/', ' ', $mainLine);
        $mainLine = preg_replace('/\( /', '(', $mainLine);
        $mainLine = preg_replace('/ \)/', ')', $mainLine);

        $matches[0] = [
            [
                $mainLine => null,
            ],
        ];

        ksort($matches);

        return $matches;
    }

   /**
    * Returns the chunk of movetext immediately preceding the given substring.
    *
    * @param string $substr
    * @return string
    */
    protected function previous(string $substr): string
    {
        $str = trim(explode("($substr", $this->filtered(false, false))[0]);
        $str = preg_replace('/\(([^()]|(?R))*\)/', '', $str);
        $str = preg_replace('/\s+/', ' ', $str);
        $exploded = explode('(', $str);

        return trim($exploded[count($exploded) - 1]);
    }
}
