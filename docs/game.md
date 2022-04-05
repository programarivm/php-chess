The `Chess\Game` class is a wrapper for the `Chess\Board` class. It is the main component of the [PHP Chess Server](https://github.com/chesslablab/chess-server). There is a one-to-one correspondence between the `Chess\Game` methods and the commands available in the [`ChessServer\Command`](https://github.com/chesslablab/chess-server/tree/master/src/Command) namespace.

Let's look at some relevant [`Chess\Game`](https://github.com/chesslablab/php-chess/blob/master/src/Game.php) methods available through the following example:

```php
use Chess\Game;

$game = new Game();

$game->play('w', 'e4');
$game->play('b', 'd5');
$game->play('w', 'exd5');
$game->play('b', 'Qxd5');
```

#### `ascii(): string`

Prints the ASCII representation of the game.

```php
echo $game->ascii();
```

Output:

```text
 r  n  b  .  k  b  n  r
 p  p  p  .  p  p  p  p
 .  .  .  .  .  .  .  .
 .  .  .  q  .  .  .  .
 .  .  .  .  .  .  .  .
 .  .  .  .  .  .  .  .
 P  P  P  P  .  P  P  P
 R  N  B  Q  K  B  N  R
```

#### `captures(): ?array`

Gets the pieces captured by both players.

```php
$captures = $game->captures();

var_export($captures);
```

Output:

```text
array (
  'w' =>
  array (
    0 =>
    (object) array(
       'capturing' =>
      (object) array(
         'id' => 'P',
         'sq' => 'e4',
      ),
       'captured' =>
      (object) array(
         'id' => 'P',
         'sq' => 'd5',
      ),
    ),
  ),
  'b' =>
  array (
    0 =>
    (object) array(
       'capturing' =>
      (object) array(
         'id' => 'Q',
         'sq' => 'd8',
      ),
       'captured' =>
      (object) array(
         'id' => 'P',
         'sq' => 'd5',
      ),
    ),
  ),
)
```

#### `castle(): ?array`

Gets the castle status.

```php
$castle = $game->castle();

var_export($castle);
```

```text
array (
  'w' =>
  array (
    'isCastled' => false,
    'O-O' => true,
    'O-O-O' => true,
  ),
  'b' =>
  array (
    'isCastled' => false,
    'O-O' => true,
    'O-O-O' => true,
  ),
)
```
#### `fen(): string`

Prints the FEN string representation of the game.

```php
echo $game->fen();
```

Output:

```text
rnb1kbnr/ppp1pppp/8/3q4/8/8/PPPP1PPP/RNBQKBNR w KQkq -
```

#### `heuristics($balanced = false, $fen = ''): array`

Gets the game's heuristic picture as an array.

```php
$heuristics = $game->heuristics(true);

var_export($heuristics);
```

Output:

```text
array (
  0 =>
  array (
    0 => 0.0,
    1 => 0.0,
    2 => -0.25,
    3 => 0.17,
    4 => 0.0,
    5 => 0.0,
    6 => -1.0,
    7 => -1.0,
  ),
  1 =>
  array (
    0 => 0.0,
    1 => -1.0,
    2 => 0.75,
    3 => -0.92,
    4 => -1.0,
    5 => -1.0,
    6 => 0.0,
    7 => 0.0,
  ),
)
```

For further information on taking heuristic pictures, please visit:

- [How to Take Normalized Heuristic Pictures](https://medium.com/geekculture/how-to-take-normalized-heuristic-pictures-79ca0df4cdec)
- [Visualizing Chess Openings Before MLP Classification](https://medium.com/geekculture/visualizing-chess-openings-before-mlp-classification-fd2a3e8c266)

#### `history(): ?array`

Returns the game's history.

```php
$history = $game->history();

var_export($history);
```

Output:

```text
array (
  0 =>
  (object) array(
     'pgn' => 'e4',
     'color' => 'w',
     'id' => 'P',
     'sq' => 'e2',
     'isCapture' => false,
     'isCheck' => false,
  ),
  1 =>
  (object) array(
     'pgn' => 'd5',
     'color' => 'b',
     'id' => 'P',
     'sq' => 'd7',
     'isCapture' => false,
     'isCheck' => false,
  ),
  2 =>
  (object) array(
     'pgn' => 'exd5',
     'color' => 'w',
     'id' => 'P',
     'sq' => 'e4',
     'isCapture' => true,
     'isCheck' => false,
  ),
  3 =>
  (object) array(
     'pgn' => 'Qxd5',
     'color' => 'b',
     'id' => 'Q',
     'sq' => 'd8',
     'isCapture' => true,
     'isCheck' => false,
  ),
)
```

#### `isCheck(): bool`

Finds out if the game is in check.

```php
$isCheck = $game->isCheck();

var_export($isCheck);
```

Output:

```text
false
```

#### `isMate(): bool`

Finds out if the game is over.

```php
$isMate = $game->isMate();

var_export($isMate);
```

Output:

```text
false
```

#### `loadFen(string $string): void`

Loads a FEN string allowing to continue a chess game.

```php
$game->loadFen('rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9');
$game->play('b', 'Bg7');

echo $game->ascii();
echo $game->fen();
```

Output:

```text
r  n  .  q  k  .  .  r
 .  .  .  .  p  p  b  p
 .  .  .  p  .  n  p  .
 .  .  p  P  .  .  .  .
 .  .  .  .  P  .  .  .
 .  .  N  .  .  .  P  .
 P  P  .  .  .  P  .  P
 R  .  B  Q  .  K  N  R
rn1qk2r/4ppbp/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR w kq -
```

#### `loadPgn(string $movetext): void`

Loads a PGN movetext allowing to continue a chess game.

```php
$game = new Game(Game::MODE_LOAD_PGN);
$game->loadPgn('1.e4 e6 2.d4 d5 3.Nc3 Nf6');
$game->play('w', 'e5');

echo $game->ascii();
echo $game->movetext();
```

Output:

```text
r  n  b  q  k  b  .  r
p  p  p  .  .  p  p  p
.  .  .  .  p  n  .  .
.  .  .  p  P  .  .  .
.  .  .  P  .  .  .  .
.  .  N  .  .  .  .  .
P  P  P  .  .  P  P  P
R  .  B  Q  K  B  N  R
1.e4 e6 2.d4 d5 3.Nc3 Nf6 4.e5
```

#### `movetext(): string`

Gets the game's movetext in text format.

```php
$movetext = $game->movetext();

var_export($movetext);
```

Output:

```text
'1.e4 d5 2.exd5 Qxd5'
```

#### `piece(string $sq): ?stdClass`

Gets a piece by its position on the board.

```php
$piece = $game->piece('d5');

var_export($piece);
```

`$piece` is a PHP object with information about the piece selected.

| Property    | Description                          |
|-------------|--------------------------------------|
| `color`     | The piece's color in PGN format      |
| `id`        | The piece's id in PGN format         |
| `sq`        | The piece's position on the board    |
| `moves`     | The piece's possible moves           |
| `enPassant` | The en passant pawn, if any          |

Output:

```text
(object) array(
   'color' => 'b',
   'id' => 'Q',
   'sq' => 'd5',
   'moves' =>
  array (
    0 => 'd6',
    1 => 'd7',
    2 => 'd8',
    3 => 'd4',
    4 => 'd3',
    5 => 'd2',
    6 => 'c5',
    7 => 'b5',
    8 => 'a5',
    9 => 'e5',
    10 => 'f5',
    11 => 'g5',
    12 => 'h5',
    13 => 'c6',
    14 => 'e6',
    15 => 'c4',
    16 => 'b3',
    17 => 'a2',
    18 => 'e4',
    19 => 'f3',
    20 => 'g2',
  ),
)
```

#### `pieces(string $color): array`

Gets the pieces by color.

```php
$pieces = $game->pieces('b');

var_export($pieces);
```
`$pieces` is an array of PHP objects with black pieces information.

| Property | Description                          |
|----------|--------------------------------------|
| `id`     | The piece's id in PGN format         |
| `sq`     | The piece's position on the board    |
| `moves`  | The piece's possible moves           |


Output:

```text
array (
  0 =>
  (object) array(
     'id' => 'R',
     'sq' => 'a8',
     'moves' =>
    array (
    ),
  ),
  1 =>
  (object) array(
     'id' => 'N',
     'sq' => 'b8',
     'moves' =>
    array (
      0 => 'a6',
      1 => 'c6',
      2 => 'd7',
    ),
  ),
  ...
  13 =>
  (object) array(
     'id' => 'P',
     'sq' => 'h7',
     'moves' =>
    array (
      0 => 'h6',
      1 => 'h5',
    ),
  ),
  14 =>
  (object) array(
     'id' => 'Q',
     'sq' => 'd5',
     'moves' =>
    array (
      0 => 'd6',
      1 => 'd7',
      2 => 'd8',
      3 => 'd4',
      4 => 'd3',
      5 => 'd2',
      6 => 'c5',
      7 => 'b5',
      8 => 'a5',
      9 => 'e5',
      10 => 'f5',
      11 => 'g5',
      12 => 'h5',
      13 => 'c6',
      14 => 'e6',
      15 => 'c4',
      16 => 'b3',
      17 => 'a2',
      18 => 'e4',
      19 => 'f3',
      20 => 'g2',
    ),
  ),
)
```

#### `play(string $color, string $pgn): bool`

Makes a move.

```php
$game->play('w', 'Nc3');

echo $game->ascii();
```

Output:

```
r  n  b  .  k  b  n  r
p  p  p  .  p  p  p  p
.  .  .  .  .  .  .  .
.  .  .  q  .  .  .  .
.  .  .  .  .  .  .  .
.  .  N  .  .  .  .  .
P  P  P  P  .  P  P  P
R  .  B  Q  K  B  N  R
```

#### `playFen(string $toShortFen)`

Plays a chess move in short FEN format; only the piece placement and the side to move are required.

```php
$game->playFen('rnb1kbnr/ppp1pppp/8/3q4/8/2N5/PPPP1PPP/R1BQKBNR b');

echo $game->ascii();
```

Output:

```
r  n  b  .  k  b  n  r
p  p  p  .  p  p  p  p
.  .  .  .  .  .  .  .
.  .  .  q  .  .  .  .
.  .  .  .  .  .  .  .
.  .  N  .  .  .  .  .
P  P  P  P  .  P  P  P
R  .  B  Q  K  B  N  R
```

#### `response()`

Returns a computer response to the current position depending on the game mode in use. It is available only if initializing a chess game in either `Game::MODE_AI` or `Game::MODE_GRANDMASTER`, otherwise it returns `null`. Also this method returns `null` if a grandmaster response to the current position can't be found.

```php
$game = new Game(Game::MODE_GRANDMASTER);

$game->play('w', 'e4');
$response = $game->response();
echo $response;
```

Output:

```
c6
```

#### `status(): stdClass`

Gets the current game's status.

```php
$status = $game->status();
```

`$status` is a PHP object with useful information about the game being played.

| Property       | Description                                |
|----------------|--------------------------------------------|
| `castling`     | The castling status for both players       |
| `isCheck`      | Whether a player is in check or not        |
| `isMate`       | Whether the game is over or not            |
| `movetext`     | The movetext                               |
| `turn`         | The current player's turn                  |

The properties of the  `$status` object are accessed this way:

```php
$game->status()->castling;
$game->status()->isCheck;
$game->status()->isMate;
$game->status()->movetext;
$game->status()->turn;
```

#### `undoMove(): ?\stdClass`

Undoes the last move returning the status of the game.

```php
$game->undoMove();

echo $game->ascii();
```

Output:

```text
 r  n  b  q  k  b  n  r
 p  p  p  .  p  p  p  p
 .  .  .  .  .  .  .  .
 .  .  .  P  .  .  .  .
 .  .  .  .  .  .  .  .
 .  .  .  .  .  .  .  .
 P  P  P  P  .  P  P  P
 R  N  B  Q  K  B  N  R
```
