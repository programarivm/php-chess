`Chess\Game` is the main component of the [PHP Chess Server](https://github.com/chesslablab/chess-server). It is a wrapper for a `Chess\Variant\Classical\Board` object to play chess online but it is also used on command line (CLI) apps as well as in APIs.

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

Let's look at the methods available through some examples. For further information please check out the tests in [tests/unit/GameTest.php](https://github.com/chesslablab/php-chess/blob/master/tests/unit/GameTest.php).

---

#### `public function play(string $color, string $pgn): bool`

Starts a game and makes a move in PGN format.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_ANALYSIS
);

$game->play('w', 'Nc3');
```

#### `public function playUci(string $color, string $uci): bool`

Starts a game and makes a move in UCI format.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_ANALYSIS
);

$game->playUci('w', 'b1c3');
```

#### `public function playFen(string $toShortFen): bool|string`

Starts a game and makes a move in short FEN format, only the piece placement and the side to move are required.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_ANALYSIS
);

$game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b');
```

#### `public function loadFen(string $string): void`

Starts a game in FEN mode. A FEN string is loaded to continue playing a game.

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

Starts a game in PGN mode. A PGN movetext is loaded to continue playing a game.

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

Returns a computer generated response to the current position.

Starts a chess game in grandmaster mode and plays `1.e4`.

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

Starts a chess game in Stockfish mode and plays `1.e4`. If provided with a .json file on instantiation `Chess\Game` will try to respond with a grandmaster move first if found in the file.

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

Starts a chess game in Stockfish mode and plays `1.e4` without any help from grandmasters.

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

Returns the state of the board.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_ANALYSIS
);

$state = $game->state();
```

`$state` is a PHP object with information about the game being played.

| Property          | Description                                         |
|-------------------|-----------------------------------------------------|
| `turn`            | The current player's turn                           |
| `castlingAbility` | The castling ability in FEN format                  |
| `movetext`        | PGN movetext                                        |
| `fen`             | FEN string                                          |
| `isCheck`         | Checks out if the current player is in check        |
| `isMate`          | Checks out if the current player is checkmated      |
| `isStalemate`     | Checks out whether the current player is stalemated |

The properties of the  `$state` object are accessed this way:

```php
$game->state()->turn;
$game->state()->castlingAbility;
$game->state()->movetext;
$game->state()->fen;
$game->state()->isCheck;
$game->state()->isMate;
$game->state()->isStalemate;
```

#### `public function getBoard(): Board`

Returns the `Chess\Variant\Classical\Board` object.
