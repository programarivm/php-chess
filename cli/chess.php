<?php

namespace ChessData\Cli;

require_once __DIR__ . '/../vendor/autoload.php';

use Chess\Game;
use Chess\Grandmaster;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class ModelPlayCli extends CLI
{
    const FILEPATH = __DIR__.'/../data/json/players.json';

    const PROMPT = 'chess > ';

    protected function setup(Options $options)
    {
        $options->setHelp('Play with an AI.');
        $options->registerArgument('fen', 'FEN string.', false);
    }

    protected function main(Options $options)
    {
        $game = new Game(
            Game::MODE_STOCKFISH,
            new Grandmaster(self::FILEPATH)
        );

        if (isset($options->getArgs()[0])) {
            $game->loadFen($options->getArgs()[0]);
        }

        do {
            $move = readline(self::PROMPT);
            if ($move === 'ascii') {
                echo $game->getBoard()->toAsciiString() . PHP_EOL;
            } elseif ($move === 'fen') {
                echo $game->getBoard()->toFen() . PHP_EOL;
            } elseif ($move !== 'quit') {
                $game->play('w', $move);
                $ai = $game->ai(['Skill Level' => 20] , ['depth' => 8]);
                $game->play('b', $ai->move);
                echo self::PROMPT . $game->getBoard()->getMovetext() . PHP_EOL;
                echo $game->getBoard()->toAsciiString();
            } else {
                break;
            }
        } while (!$game->getBoard()->isMate());
    }
}

$cli = new ModelPlayCli();
$cli->run();
