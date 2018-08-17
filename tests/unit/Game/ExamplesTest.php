<?php

namespace PGNChess\Tests\Unit\Game;

use PGNChess\Game;
use PHPUnit\Framework\TestCase;

class ExamplesTest extends TestCase
{
    const EXAMPLES_FOLDER = __DIR__.'/../../../examples';

    protected $game;

    public function setUp()
    {
        $this->game = new Game();
    }

    public function tearDown()
    {
        $this->game = null;
    }

    /**
     * @dataProvider playGameData
     * @test
     */
    public function play_game($filename)
    {
        $pgn = file_get_contents(self::EXAMPLES_FOLDER."/$filename");

        $pairs = array_filter(preg_split('/[0-9]+\./', $pgn));

        $moves = [];
        foreach ($pairs as $pair) {
            $moves[] = array_values(array_filter(explode(' ', $pair)));
        }

        $moves = array_values(array_filter($moves));

        for ($i = 0; $i < count($moves); ++$i) {
            $whiteMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][0]));
            $this->assertEquals(true, $this->game->play('w', $whiteMove));
            if (isset($moves[$i][1])) {
                $blackMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][1]));
                $this->assertEquals(true, $this->game->play('b', $blackMove));
            }
        }
    }

    public function playGameData()
    {
        $data = [];
        for ($i = 1; $i <= 85; ++$i) {
            $i <= 9 ? $data[] = ["game-0$i.pgn"] : $data[] = ["game-$i.pgn"];
        }

        return $data;
    }
}
