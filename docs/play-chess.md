# Play Chess

## Play Randomly

✨ Sometimes you want to play a random game of chess.

You could loop 50 times and play a move by instantiating the [Chess\Computer\RandomMove](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Computer/RandomMoveTest.php) class like in the example below.

```php
use Chess\Computer\RandomMove;
use Chess\Variant\Classical\Board;

$board = new Board();

for ($i = 0; $i < 50; $i++) {
    if ($move = (new RandomMove($board))->move()) {
        $board->play($board->turn, $move['pgn']);
    }
}

echo $board->movetext();
```

```text
1.e4 Nc6 2.Qf3 g6 3.b3 Rb8 4.Bb5 Nh6 5.Ke2 Na5 6.c3 Ng4 7.d3 h6 8.h4 Ra8 9.Kd1 b6 10.Ba4 h5 11.b4 Rh7 12.Bc2 Rh8 13.Ke1 a6 14.Kd1 Nb3 15.d4 Na5 16.g3 c6 17.Ne2 Ne5 18.Nf4 d6 19.Nxg6 Kd7 20.b5 Qe8 21.Na3 e6 22.Bd3 Rb8 23.dxe5 dxe5 24.Bb2 Rg8 25.Qg4 Ra8
```

The result obtained is a 24 move game since a move is considered to be completed after both players have played a turn.

## Play Like a Grandmaster

✨ The [players.json](https://github.com/chesslablab/chess-server/blob/main/data/players.json) file in the [Chess Server](https://github.com/chesslablab/chess-server) contains thousands of games by titled FIDE players. This file can be generated with the command line tools available in the [Chess Data](https://github.com/chesslablab/chess-data) repo.

[Chess\Computer\GrandmasterMove](https://github.com/chesslablab/php-chess/blob/main/src/Computer/GrandmasterMove.php) figures out the next move to be made based on the players.json file that is passed to its constructor.

```php
use Chess\Computer\GrandmasterMove;
use Chess\Variant\Classical\Board;

$board = new Board();
$board->play('w', 'e4');

$gmMove = (new GrandmasterMove(__DIR__.'/../data/json/players.json'))->move($board);

print_r($gmMove);
```

```text
Array
(
    [pgn] => c5
    [game] => Array
        (
            [Event] => Tilburg
            [Site] => Tilburg
            [Date] => 1993.??.??
            [White] => Morozevich, Alexander
            [Black] => Adams, Michael
            [Result] => 1-0
            [ECO] => B23
            [movetext] => 1.e4 c5 2.Nc3 Nc6 3.f4 g6 4.Nf3 Bg7 5.Bb5 Nd4 6.Nxd4 cxd4 7.Ne2 a6 8.Ba4 b5 9.Bb3 e6 10.O-O Ne7 11.d3 O-O 12.Qe1 f5 13.Bd2 Nc6 14.Kh1 Bb7 15.Ng1 h6 16.Nf3 Kh7 17.Qg3 Qe7 18.Rae1 a5 19.a3 Rab8 20.Nh4 Qf7 21.Qh3 Ba8 22.Rf3 a4 23.Ba2 b4 24.Bc1 b3 25.cxb3 axb3 26.Bb1 d6 27.Bd2 Kg8 28.g4 Ne7 29.gxf5 gxf5 30.Rg3 Kh7 31.Nf3 Bf6 32.Ng5+ Bxg5 33.fxg5 Ng6 34.Qxh6+ Kg8 35.h4 f4 36.Rh3 Rb7 37.h5 Ne5 38.Rg1 Rc7 39.Rh4 Qe8 40.g6
        )

)
```

🎉 Let's now put our knowledge of chess openings to the test.

## Play Computer

✨ The Universal Chess Interface (UCI) is an open communication protocol that enables chess engines to communicate with user interfaces.

PHP Chess provides the [Chess\UciEngine\UciEngine](https://github.com/chesslablab/php-chess/blob/main/tests/unit/UciEngine/UciEngineTest.php) class representing an UCI engine. To follow this tutorial make sure to install Stockfish if you haven't already.

```text
sudo apt-get install stockfish
```

Then, you are set up to play chess against the computer as described in the following example.

```php
use Chess\UciEngine\UciEngine;
use Chess\UciEngine\Details\Limit;
use Chess\Variant\Classical\Board;

$board = new Board();
$board->play('w', 'e4');

$limit = new Limit();
$limit->depth = 3;

$stockfish = (new UciEngine('/usr/games/stockfish'))->setOption('Skill Level', 9);
$analysis = $stockfish->analysis($board, $limit);

$board->playLan('b', $analysis['bestmove']);

echo $board->movetext();
```

```text
1.e4 Nf6
```

You may want to play against Stockfish starting from a particular FEN with the help of [Chess\Variant\Classical\FenToBoardFactory](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Variant/Classical/FenToBoardFactoryTest.php).

```php
use Chess\UciEngine\UciEngine;
use Chess\UciEngine\Details\Limit;
use Chess\Variant\Classical\FenToBoardFactory;

$board = FenToBoardFactory::create('4k2r/pp1b1pp1/8/3pPp1p/P2P1P2/1P3N2/1qr3PP/R3QR1K w k -');

$limit = new Limit();
$limit->depth = 12;

$stockfish = (new UciEngine('/usr/games/stockfish'))->setOption('Skill Level', 20);
$analysis = $stockfish->analysis($board, $limit);

$board->playLan('w', $analysis['bestmove']);

echo $board->movetext();
```

```text
1.Rb1
```

The FEN is converted to a chess board object. Then Stockfish's depth limit is set to `12` and the skill level to `20`.

The same thing goes to starting a game from a particular SAN movetext. As you can see in the example below, [Chess\Play\SanPlay](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Play/SanPlayTest.php) is used for this purpose.

```php
use Chess\Play\SanPlay;
use Chess\UciEngine\UciEngine;
use Chess\UciEngine\Details\Limit;

$movetext = '1.d4 Nf6 2.c4 c5 3.d5 e6 4.Nc3 exd5 5.cxd5 d6 6.e4 g6 7.Nf3 Bg7';

$board = (new SanPlay($movetext))->validate()->board;

$limit = new Limit();
$limit->depth = 12;

$stockfish = (new UciEngine('/usr/games/stockfish'))->setOption('Skill Level', 20);
$analysis = $stockfish->analysis($board, $limit);

$board->playLan('w', $analysis['bestmove']);

echo $board->movetext();
```

```text
1.d4 Nf6 2.c4 c5 3.d5 e6 4.Nc3 exd5 5.cxd5 d6 6.e4 g6 7.Nf3 Bg7 8.h3
```

🎉 Can you beat the computer? Keep it up!
