<?php

namespace PGNChess\PGN\File;

use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate;

/**
 * Syntax class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Syntax
{
    public function check()
    {
        // TODO ...

        return true;
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
}
