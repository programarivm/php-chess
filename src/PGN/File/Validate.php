<?php

namespace PGNChess\PGN\File;

use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate as PgnValidate;

class Validate extends AbstractFile
{
    private $result = [];

    public function __construct(string $filepath)
    {
        parent::__construct($filepath);

        $this->result = (object) [
            'valid' => 0,
            'errors' => []
        ];
    }

    public function syntax(): \stdClass
    {
        $tags = [];
        $movetext = '';
        if ($file = fopen($this->filepath, 'r')) {
            while (!feof($file)) {
                $line = preg_replace('~[[:cntrl:]]~', '', fgets($file));
                try {
                    $tag = PgnValidate::tag($line);
                    $tags[$tag->name] = $tag->value;
                } catch (\Exception $e) {
                    if (!Tag::isStr($tags) && $this->line->startsMovetext($line)) {
                        $this->result->errors[] = ['tags' => array_filter($tags)];
                        Tag::reset($tags);
                        $movetext = '';
                    } elseif (Tag::isStr($tags) && (($this->line->isMovetext($line) || $this->line->endsMovetext($line)))) {
                        $movetext .= ' ' . $line;
                        !PgnValidate::movetext($movetext)
                            ? $this->result->errors[] = [
                                'tags' => array_filter($tags),
                                'movetext' => trim($movetext)]
                            : $this->result->valid += 1;
                        Tag::reset($tags);
                        $movetext = '';
                    } elseif (Tag::isStr($tags)) {
                        $movetext .= ' ' . $line;
                    }
                }
            }
            fclose($file);
        }

        if (empty($this->result->errors)) {
            unset($this->result->errors);
        }

        return $this->result;
    }
}
