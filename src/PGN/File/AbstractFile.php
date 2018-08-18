<?php

namespace PGNChess\PGN\File;

use PGNChess\PGN\Tag;

/**
 * AbstractFile class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
abstract class AbstractFile
{
    protected $filepath;

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }

    protected function startsMovetext($line)
    {
        return $this->startsWith($line, '1.');
    }

    protected function endsMovetext($line)
    {
        return $this->endsWith($line, '0-1') || $this->endsWith($line, '1-0') || $this->endsWith($line, '1/2-1/2');
    }

    protected function startsWith($haystack, $needle)
    {
        return strcasecmp(substr($haystack, 0, strlen($needle)), $needle) === 0;
    }

    protected function endsWith($haystack, $needle)
    {
        return strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)), $needle) === 0;
    }

    private function hasStrTags($tags)
    {
        return isset($tags[Tag::EVENT]) &&
            isset($tags[Tag::SITE]) &&
            isset($tags[Tag::DATE]) &&
            isset($tags[Tag::ROUND]) &&
            isset($tags[Tag::WHITE]) &&
            isset($tags[Tag::BLACK]) &&
            isset($tags[Tag::RESULT]);
    }

    protected function resetTags()
    {
        foreach (Tag::getConstants() as $key => $value) {
            $tags[$value] = null;
        }

        return $tags;
    }
}
