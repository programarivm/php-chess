<?php

namespace Chess;

use Chess\Variant\Classical\Board;

/**
 * Grandmaster
 *
 * Figures out the next move to be made based on the JSON file that is passed to
 * its constructor. Typically this file would contain games by titled FIDE
 * players.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class Grandmaster
{
    /**
     * Chess games.
     *
     * @var \RecursiveIteratorIterator
     */
    private \RecursiveIteratorIterator $items;

    /**
     * Constructor.
     *
     * @param string $filepath
     */
    public function __construct(string $filepath)
    {
        $contents = file_get_contents($filepath);

        $this->items = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator(json_decode($contents, true)),
            \RecursiveIteratorIterator::SELF_FIRST);
    }

    /**
     * Returns a chess move.
     *
     * @param \Chess\Variant\Classical\Board $board
     * @return null|object
     */
    public function move(Board $board): ?object
    {
        $movetext = $board->getMovetext();
        if ($found = $this->find($movetext)) {
            return (object) [
                'move' => $this->next($found[0]['movetext'], $movetext),
                'game' => $found[0],
            ];
        }

        return null;
    }

    /**
     * Finds a chess game by movetext.
     *
     * @param string $movetext
     * @return array
     */
    protected function find(string $movetext): array
    {
        $found = [];
        foreach ($this->items as $item) {
            if (isset($item['movetext'])) {
                if (str_starts_with($item['movetext'], $movetext)) {
                    $found[] = $item;
                }
            }
        }
        shuffle($found);

        return $found;
    }

    /**
     * Finds out the next move to be made.
     *
     * @param string $haystack
     * @param string $needle
     * @return string
     */
    protected function next(string $haystack, string $needle): string
    {
        $moves = array_filter(explode(' ', str_replace($needle, '', $haystack)));
        $current = explode('.', current($moves));
        isset($current[1]) ? $move = $current[1] : $move = $current[0];

        return $move;
    }
}
