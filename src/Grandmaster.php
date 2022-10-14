<?php

namespace Chess;

use Chess\Game;

class Grandmaster
{
    private \RecursiveIteratorIterator $items;

    public function __construct(string $filepath)
    {
        $contents = file_get_contents($filepath);

        $this->items = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator(json_decode($contents, true)),
            \RecursiveIteratorIterator::SELF_FIRST);
    }

    /**
     * Figures out the next move to be made.
     *
     * @param \Chess\Game
     * @return null|object
     */
    public function move(Game $game): ?object
    {
        $movetext = $game->getBoard()->getMovetext();
        if ($found = $this->find($movetext)) {
            return (object) [
                'move' => $this->next($found[0]['movetext'], $movetext),
                'game' => $found[0],
            ];
        }

        return null;
    }

    protected function find(string $movetext)
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

    protected function next(string $haystack, string $needle): string
    {
        $moves = array_filter(explode(' ', str_replace($needle, '', $haystack)));
        $current = explode('.', current($moves));
        isset($current[1]) ? $move = $current[1] : $move = $current[0];

        return $move;
    }
}
