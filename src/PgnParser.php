<?php

namespace Chess;

use Chess\Exception\UnknownNotationException;
use Chess\Movetext\SanMovetext;
use Chess\Variant\AbstractNotation;
use Chess\Variant\Classical\PGN\Tag;
use Chess\Variant\Classical\PGN\Termination;

/**
 * PGN Parser
 *
 * Syntax validation for text files containing multiple games including tag
 * pairs.
 */
class PgnParser
{
    /**
     * The chess move in abstract notation.
     *
     * @var \Chess\Variant\AbstractNotation
     */
    private AbstractNotation $move;

    /**
     * Filepath.
     *
     * @var string
     */
    private string $filepath;

    /**
     * Tag.
     *
     * @var \Chess\Variant\Classical\PGN\Tag
     */
    private Tag $tag;

    /**
     * The result of the parsing.
     *
     * @var array
     */
    private array $result;

    /**
     * Handle the validation.
     *
     * @var \Closure
     */
    private \Closure $handleValidationCallback;

    /**
     * @param string $filepath
     */
    public function __construct(AbstractNotation $move, string $filepath)
    {
        $this->move = $move;
        $this->filepath = $filepath;
        $this->tag = new Tag();
        $this->result = [
            'total' => 0,
            'valid' => 0,
        ];
    }

    /**
     * Returns the result of the parsing.
     *
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * Syntax validation.
     */
    public function parse(): void
    {
        $tags = [];
        $movetext = '';
        $file = new \SplFileObject($this->filepath);
        while (!$file->eof()) {
            $line = rtrim($file->fgets());
            try {
                $valid = $this->tag->validate($line);
                $tags[$valid['name']] = $valid['value'];
            } catch (UnknownNotationException $e) {
                if ($this->isOneLinerMovetext($line)) {
                    if (!array_diff($this->tag->mandatory(), array_keys($tags)) &&
                        $validMovetext = (new SanMovetext($this->move, $line))->validate()
                    ) {
                        $this->handleValidation($tags, $validMovetext);
                        $this->result['valid'] += 1;
                    }
                    $tags = [];
                    $movetext = '';
                    $this->result['total'] += 1;
                } elseif ($this->startsMovetext($line)) {
                    if (!array_diff($this->tag->mandatory(), array_keys($tags))) {
                        $movetext .= ' ' . $line;
                    }
                } elseif ($this->endsMovetext($line)) {
                    $movetext .= ' ' . $line;
                    if ($validMovetext = (new SanMovetext($this->move, $movetext))->validate()) {
                        $this->handleValidation($tags, $validMovetext);
                        $this->result['valid'] += 1;
                    }
                    $tags = [];
                    $movetext = '';
                    $this->result['total'] += 1;
                } else {
                    $movetext .= ' ' . $line;
                }
            }
        }
    }

    /**
     * Set the validation callback.
     * 
     * @param \Closure $handleValidationCallback
     */
    public function onValidation(\Closure $handleValidationCallback): void
    {
        $this->handleValidationCallback = $handleValidationCallback;
    }

    /**
     * Handle the validation.
     * 
     * @param array $tags
     * @param string $movetext
     */
    private function handleValidation(array $tags, string $movetext): void
    {
        call_user_func($this->handleValidationCallback, $tags, $movetext);
    }

    /**
     * Returns true if the movetext contains one line.
     * 
     * @param string $line
     * @return bool
     */
    private function isOneLinerMovetext(string $line): bool
    {
        return $this->startsMovetext($line) && $this->endsMovetext($line);
    }

    /**
     * Returns true if it is the first line of the movetext.
     * 
     * @param string $line
     * @return bool
     */
    private function startsMovetext(string $line): bool
    {
        return $this->startsWith($line, '1.');
    }

    /**
     * Returns true if it is the last line of the movetext.
     * 
     * @param string $line
     * @return bool
     */
    private function endsMovetext(string $line): bool
    {
        return $this->endsWith($line, Termination::WHITE_WINS) ||
            $this->endsWith($line, Termination::BLACK_WINS) ||
            $this->endsWith($line, Termination::DRAW) ||
            $this->endsWith($line, Termination::UNKNOWN);
    }

    /**
     * Returns true if the haystack starts with the needle.
     * 
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    private function startsWith(string $haystack, string $needle): bool
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }

    /**
     * Returns true if the haystack ends with the needle.
     * 
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    private function endsWith(string $haystack, string $needle): bool
    {
        return substr($haystack, -strlen($needle)) === $needle;
    }
}