<?php

namespace PGNChess\PGN\File;

use PGNChess\Exception\PgnFileCharacterEncodingException;
use PGNChess\PGN\Symbol;
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
        $content = file_get_contents($filepath);
        $encoding = mb_detect_encoding($content);
        if ($encoding !== 'ASCII' && $encoding !== 'UTF-8') {
            throw new PgnFileCharacterEncodingException(
                "Character encoding detected: $encoding. Needs to be UTF-8."
            );
        }

        $this->filepath = $filepath;
    }

    protected function startsMovetext($line)
    {
        return $this->startsWith($line, '1.');
    }

    protected function endsMovetext($line)
    {
        return $this->endsWith($line, Symbol::RESULT_WHITE_WINS) ||
            $this->endsWith($line, Symbol::RESULT_BLACK_WINS) ||
            $this->endsWith($line, Symbol::RESULT_DRAW) ||
            $this->endsWith($line, Symbol::RESULT_UNKNOWN);
    }

    protected function startsWith($haystack, $needle)
    {
        return strcasecmp(substr($haystack, 0, strlen($needle)), $needle) === 0;
    }

    protected function endsWith($haystack, $needle)
    {
        return strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)), $needle) === 0;
    }

    protected function hasStrTags($tags)
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
