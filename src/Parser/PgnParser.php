<?php

namespace Chess\Parser;

use Chess\Exception\UnknownNotationException;
use Chess\Movetext\SanMovetext;
use Chess\Parser\PgnLine;
use Chess\Variant\Classical\PGN\Tag;
use Chess\Variant\Classical\PGN\Move;

class PgnParser
{
    protected string $filepath;

    protected PgnLine $line;

    protected object $result;

    protected \Closure $callback;

    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
        $this->line = new PgnLine();
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
                if ($this->line->isOneLinerMovetext($line)) {
                    if (!array_diff($tag->mandatory(), array_keys($tags)) &&
                        $validMovetext = (new SanMovetext($move, $line))->validate()
                    ) {
                        if ($this->handle($tags, $validMovetext)) {
                            $this->result->valid++;
                        }
                    }
                    $tags = [];
                    $movetext = '';
                    $this->result->total++;
                } elseif ($this->line->startsMovetext($line)) {
                    if (!array_diff($tag->mandatory(), array_keys($tags))) {
                        $movetext .= ' ' . $line;
                    }
                } elseif ($this->line->endsMovetext($line)) {
                    $movetext .= ' ' . $line;
                    if ($validMovetext = (new SanMovetext($move, $movetext))->validate()) {
                        if ($this->handle($tags, $validMovetext)) {
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

    public function onValidate($callback): void
    {
        $this->callback = $callback;
    }

    protected function handle(array $tags, string $movetext): void
    {
        call_user_func($this->callback, $tags, $movetext);
    }
}