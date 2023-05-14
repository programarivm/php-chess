The `Chess\Game` class is the building block of the [PHP Chess Server](https://github.com/chesslablab/chess-server). It is a wrapper for a chess board object to play chess online. It is also used in the [Chess API](https://www.chesslablab.com/documentation/) as well as in [command line](https://php-chess.readthedocs.io/en/latest/cli/) (CLI) apps.

Variants:

- `Chess\Game::VARIANT_960`
- `Chess\Game::VARIANT_CAPABLANCA_80`
- `Chess\Game::VARIANT_CAPABLANCA_100`
- `Chess\Game::VARIANT_CLASSICAL`

Modes:

- `Chess\Game:MODE_ANALYSIS`
- `Chess\Game:MODE_GM`
- `Chess\Game:MODE_FEN`
- `Chess\Game:MODE_PGN`
- `Chess\Game:MODE_PLAY`
- `Chess\Game:MODE_STOCKFISH`

Let's look at the methods available in the `Chess\Game` class through some examples. For further information please check out these [tests](https://github.com/chesslablab/php-chess/blob/master/tests/unit/GameTest.php).

---

#### `public function play(string $color, string $pgn): bool`

The following code snippet starts a classical game in analysis mode and makes the first move in PGN format.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_ANALYSIS
);

$game->play('w', 'Nc3');
```

#### `public function playLan(string $color, string $lan): bool`

This one also starts a classical game in analysis mode making the first move in long algebraic notation instead.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_ANALYSIS
);

$game->playLan('w', 'b1c3');
```

#### `public function loadFen(string $fen): void`

Described next is how to start a classical game in FEN mode. After a FEN string is successfully loaded the game can be continued by making moves either in PGN or in long algebraic notation.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_FEN
);

$game->loadFen('rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9');
$game->play('b', 'Bg7');
```

#### `public function loadPgn(string $movetext): void`

This is how to start a game in PGN mode. After a PGN movetext is successfully loaded the game can be continued by making moves either in PGN or in long algebraic notation.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_PGN
);

$game->loadPgn('1.e4 e6 2.d4 d5 3.Nc3 Nf6');
$game->play('w', 'e5');
```

#### `public function ai(array $options = [], array $params = []): ?object`

The code snippet below returns a computer generated response to the current position. It starts a classical chess game in grandmaster mode to then play `1.e4`.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_GM,
    __DIR__.'/../data/players.json'
);

$game->play('w', 'e4');

$ai = $game->ai();

print_r($ai);
```
```
stdClass Object
(
    [move] => c5
    [game] => Array
        (
            [Event] => Wijk m
            [Site] => Wijk aan Zee
            [Date] => 1993.??.??
            [White] => Adams, Michael
            [Black] => Hodgson, Julian M
            [Result] => 1/2-1/2
            [ECO] => B99
            [movetext] => 1.e4 c5 2.Nc3 d6 3.Nge2 Nf6 4.d4 cxd4 5.Nxd4 a6 6.Bg5 e6 7.f4 Be7 8.Qf3 Qc7 9.O-O-O Nbd7 10.g4 b5 11.Bxf6 Nxf6 12.g5 Nd7 13.f5 Bxg5+ 14.Kb1 Ne5 15.Qh5 Qd8 16.Nxe6 Bxe6 17.fxe6 O-O 18.Bh3 g6 19.Qe2 fxe6 20.Bxe6+ Kh8 21.h4 Bf6 22.h5 g5 23.Rhf1 h6 24.Nd5 Bg7 25.Rxf8+ Qxf8 26.Rf1 Qd8 27.Ne3 Qe7 28.Bb3 Rf8 29.Nf5 Qf6 30.c3 g4 31.a3 Qg5 32.Rd1 Qxh5 33.Rxd6 Qg5 34.Rxa6 g3 35.Qg2 Nd3 36.Rd6 Nf4
        )

)
```

A `Chess\Game` object can also be started in Stockfish mode. If provided with a `.json` file on instantiation it'll try to respond with a grandmaster move first, if found in the file.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_STOCKFISH,
    __DIR__.'/../data/players.json'
);

$game->play('w', 'e4');

$ai = $game->ai(['Skill Level' => 9], ['depth' => 3]);

print_r($ai);
```
```
stdClass Object
(
    [move] => c5
    [game] => Array
        (
            [Event] => Wijk m
            [Site] => Wijk aan Zee
            [Date] => 1993.??.??
            [White] => Adams, Michael
            [Black] => Hodgson, Julian M
            [Result] => 1/2-1/2
            [ECO] => B99
            [movetext] => 1.e4 c5 2.Nc3 d6 3.Nge2 Nf6 4.d4 cxd4 5.Nxd4 a6 6.Bg5 e6 7.f4 Be7 8.Qf3 Qc7 9.O-O-O Nbd7 10.g4 b5 11.Bxf6 Nxf6 12.g5 Nd7 13.f5 Bxg5+ 14.Kb1 Ne5 15.Qh5 Qd8 16.Nxe6 Bxe6 17.fxe6 O-O 18.Bh3 g6 19.Qe2 fxe6 20.Bxe6+ Kh8 21.h4 Bf6 22.h5 g5 23.Rhf1 h6 24.Nd5 Bg7 25.Rxf8+ Qxf8 26.Rf1 Qd8 27.Ne3 Qe7 28.Bb3 Rf8 29.Nf5 Qf6 30.c3 g4 31.a3 Qg5 32.Rd1 Qxh5 33.Rxd6 Qg5 34.Rxa6 g3 35.Qg2 Nd3 36.Rd6 Nf4
        )

)
```

The `players.json` file, which is an optional parameter, is to be generated with the command line tools available in the [Chess Data](https://github.com/chesslablab/chess-data) repository. The chess game can be started in Stockfish mode without any help from grandmasters.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_STOCKFISH
);

$game->play('w', 'e4');

$ai = $game->ai(['Skill Level' => 9], ['depth' => 3]);

print_r($ai);
```
```
stdClass Object
(
    [move] => d5
)
```

#### `public function state(): object`

Returns the state of the game in `stdClass` format.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_ANALYSIS
);

$state = $game->state();
```

`$state` is a PHP object with information about the game being played.

| Property               | Description                                             |
|------------------------|---------------------------------------------------------|
| `turn`                 | The current player's turn.                              |
| `castlingAbility`      | The castling ability in FEN format.                     |
| `movetext`             | PGN movetext.                                           |
| `fen`                  | FEN string.                                             |
| `isCheck`              | Checks out if the current player is in check.           |
| `isMate`               | Checks out if the current player is checkmated.         |
| `isStalemate`          | Checks out whether the current player is stalemated.    |
| `isFivefoldRepetition` | Checks out whether the same position occurs five times. |

The properties of the  `$state` object are accessed this way:

```php
$game->state()->turn;
$game->state()->castlingAbility;
$game->state()->movetext;
$game->state()->fen;
$game->state()->isCheck;
$game->state()->isMate;
$game->state()->isStalemate;
$game->state()->isFivefoldRepetition;
```

#### `public function getBoard(): ClassicalBoard`

`Chess\Game` is a wrapper for an object of type `Chess\Variant\Classical\Board` and this method returns the underlying chessboard.
