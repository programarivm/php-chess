<?php

namespace Chess;

use Chess\Exception\UnknownNotationException;
use Chess\Movetext\SanMovetext;
use Chess\Variant\Classical\PGN\Tag;
use Chess\Variant\Classical\PGN\Termination;
use Chess\Variant\Classical\PGN\Move;

/**
 * PGN Parser
 *
 * Parses a text file containing multiple games including tag pairs.
 */
class PgnParser
{
    /**
     * The filepath.
     *
     * @var string
     */
    protected string $filepath;

    protected object $result;

    protected \Closure $handleValidationCallback;

    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
        $this->result = (object) [
            'total' => 0,
            'valid' => 0,
        ];
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function parse(): void
    {
        $tags = [];
        $movetext = '';
        $file = new \SplFileObject($this->filepath);
        $tag = new Tag();
        $move = new Move();
        while (!$file->eof()) {
            $line = rtrim($file->fgets());
            try {
                $valid = $tag->validate($line);
                $tags[$valid['name']] = $valid['value'];
            } catch (UnknownNotationException $e) {
                if ($this->isOneLinerMovetext($line)) {
                    if (!array_diff($tag->mandatory(), array_keys($tags)) &&
                        $validMovetext = (new SanMovetext($move, $line))->validate()
                    ) {
                        if ($this->handleValidation($tags, $validMovetext)) {
                            $this->result->valid++;
                        }
                    }
                    $tags = [];
                    $movetext = '';
                    $this->result->total++;
                } elseif ($this->startsMovetext($line)) {
                    if (!array_diff($tag->mandatory(), array_keys($tags))) {
                        $movetext .= ' ' . $line;
                    }
                } elseif ($this->endsMovetext($line)) {
                    $movetext .= ' ' . $line;
                    if ($validMovetext = (new SanMovetext($move, $movetext))->validate()) {
                        if ($this->handleValidation($tags, $validMovetext)) {
                            $this->result->valid++;
                        }
                    }
                    $tags = [];
                    $movetext = '';
                    $this->result->total++;
                } else {
                    $movetext .= ' ' . $line;
                }
            }
        }
    }

    public function onValidation(\Closure $handleValidationCallback): void
    {
        $this->handleValidationCallback = $handleValidationCallback;
    }

    protected function handleValidation(array $tags, string $movetext): void
    {
        call_user_func($this->handleValidationCallback, $tags, $movetext);
    }

    protected function isOneLinerMovetext(string $line): bool
    {
        return $this->startsMovetext($line) && $this->endsMovetext($line);
    }

    protected function startsMovetext(string $line): bool
    {
        return $this->startsWith($line, '1.');
    }

    protected function endsMovetext(string $line): bool
    {
        return $this->endsWith($line, Termination::WHITE_WINS) ||
            $this->endsWith($line, Termination::BLACK_WINS) ||
            $this->endsWith($line, Termination::DRAW) ||
            $this->endsWith($line, Termination::UNKNOWN);
    }

    protected function startsWith(string $haystack, string $needle): bool
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }

    protected function endsWith(string $haystack, string $needle): bool
    {
        return substr($haystack, -strlen($needle)) === $needle;
    }
}