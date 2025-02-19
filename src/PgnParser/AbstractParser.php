<?php

namespace Chess\PgnParser;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\PGN\Tag;
use Chess\Variant\Classical\PGN\Move;
use Chess\Movetext\SanMovetext;

abstract class AbstractParser
{
    protected $filepath;

    protected $line;

    protected $result;

    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;

        $this->line = new FileLine();

        $this->result = (object) [
            'total' => 0,
            'valid' => 0,
        ];
    }

    public function getResult()
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
                        if ($this->onValidate($tags, $validMovetext)) {
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
                        if ($this->onValidate($tags, $validMovetext)) {
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

    abstract protected function onValidate(array $tags, string $movetext);
}