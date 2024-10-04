<?php

namespace Chess\Computer;

use Chess\Variant\Classical\Board;

class GrandmasterMove
{
    private \RecursiveIteratorIterator $games;

    public function __construct(string $filepath)
    {
        $contents = file_get_contents($filepath);

        $this->games = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator(json_decode($contents, true)),
            \RecursiveIteratorIterator::SELF_FIRST
        );
    }

    public function move(Board $board): ?array
    {
        $movetext = $board->movetext();
        if ($found = $this->find($movetext)) {
            return [
                'pgn' => $this->next($found[0]['movetext'], $movetext),
                'game' => $found[0],
            ];
        }

        return null;
    }

    protected function find(string $movetext): array
    {
        $found = [];
        foreach ($this->games as $val) {
            if (isset($val['movetext'])) {
                if (str_starts_with($val['movetext'], $movetext)) {
                    $found[] = $val;
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
