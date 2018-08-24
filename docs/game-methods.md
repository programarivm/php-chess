### Game methods

#### `isCheck()`

Finds out if the game is in check.

```php
$isCheck = $game->isCheck();
```

#### `isMate()`

Finds out if the game is over.

```php
$isMate = $game->isMate();
```

#### `status()`

Gets the current game's status.

    $status = $game->status();

`$status` is a PHP object containing useful information about the game being played.

| Property       | Description                                |
|----------------|--------------------------------------------|
| `turn`         | The current player's turn                  |
| `squares`      | Free/used squares on the board             |
| `control`      | Squares controlled by both players         |
| `castling`     | The castling status of the two kings       |

The following sequence of moves:

```php
<?php

$game = new Game;

$game->play('w', 'd4');
$game->play('b', 'c6');
$game->play('w', 'Bf4');
$game->play('b', 'd5');
$game->play('w', 'Nc3');
$game->play('b', 'Nf6');
$game->play('w', 'Bxb8');
$game->play('b', 'Rxb8');

$status = $game->status();
```

Will generate this `$status` object:

    stdClass Object
    (
    [turn] => w
    [squares] => stdClass Object
        (
            [used] => stdClass Object
                (
                    [w] => Array
                        (
                            [0] => a1
                            [1] => d1
                            [2] => e1
                            [3] => f1
                            [4] => g1
                            [5] => h1
                            [6] => a2
                            [7] => b2
                            [8] => c2
                            [9] => e2
                            [10] => f2
                            [11] => g2
                            [12] => h2
                            [13] => d4
                            [14] => c3
                        )

                    [b] => Array
                        (
                            [0] => c8
                            [1] => d8
                            [2] => e8
                            [3] => f8
                            [4] => h8
                            [5] => a7
                            [6] => b7
                            [7] => e7
                            [8] => f7
                            [9] => g7
                            [10] => h7
                            [11] => c6
                            [12] => d5
                            [13] => f6
                            [14] => b8
                        )

                )

            [free] => Array
                (
                    [0] => a3
                    [1] => a4
                    [2] => a5
                    [3] => a6
                    [4] => a8
                    [5] => b1
                    [6] => b3
                    [7] => b4
                    [8] => b5
                    [9] => b6
                    [10] => c1
                    [11] => c4
                    [12] => c5
                    [13] => c7
                    [14] => d2
                    [15] => d3
                    [16] => d6
                    [17] => d7
                    [18] => e3
                    [19] => e4
                    [20] => e5
                    [21] => e6
                    [22] => f3
                    [23] => f4
                    [24] => f5
                    [25] => g3
                    [26] => g4
                    [27] => g5
                    [28] => g6
                    [29] => g8
                    [30] => h3
                    [31] => h4
                    [32] => h5
                    [33] => h6
                )

        )

    [control] => stdClass Object
        (
            [space] => stdClass Object
                (
                    [w] => Array
                        (
                            [0] => a3
                            [1] => a4
                            [2] => b1
                            [3] => b3
                            [4] => b5
                            [5] => c1
                            [6] => c5
                            [7] => d2
                            [8] => d3
                            [9] => e3
                            [10] => e4
                            [11] => e5
                            [12] => f3
                            [13] => g3
                            [14] => h3
                        )

                    [b] => Array
                        (
                            [0] => a5
                            [1] => a6
                            [2] => a8
                            [3] => b5
                            [4] => b6
                            [5] => c4
                            [6] => c7
                            [7] => d6
                            [8] => d7
                            [9] => e4
                            [10] => e6
                            [11] => f5
                            [12] => g4
                            [13] => g6
                            [14] => g8
                            [15] => h3
                            [16] => h5
                            [17] => h6
                        )

                )

            [attack] => stdClass Object
                (
                    [w] => Array
                        (
                            [0] => d5
                        )

                    [b] => Array
                        (
                        )

                )

        )

    [castling] => stdClass Object
        (
            [w] => stdClass Object
                (
                    [castled] =>
                    [O-O] => 1
                    [O-O-O] => 1
                )

            [b] => stdClass Object
                (
                    [castled] =>
                    [O-O] => 1
                    [O-O-O] =>
                )

        )

    )

The status properties of the game can be easily accessed this way:

```php
<?php

// current turn
$game->status()->turn;

// used/free squares
$game->status()->squares->used;
$game->status()->squares->free;

// white's control
$game->status()->control->space->{'w'};
$game->status()->control->attack->{'w'};

// black's control
$game->status()->control->space->{'b'};
$game->status()->control->attack->{'b'};

// white's castling
$game->status()->castling->{'w'}->castled;
$game->status()->castling->{'w'}->{'O-O'};
$game->status()->castling->{'w'}->{'O-O-O'};

// black's castling
$game->status()->castling->{'b'}->castled;
$game->status()->castling->{'b'}->{'O-O'};
$game->status()->castling->{'b'}->{'O-O-O'};
```

#### `piece()`

Gets a piece by its position on the board.

    $piece = $game->piece('c8');

`$piece` is a PHP object containing information about the chess piece selected:

| Property       | Description                                |
|----------------|--------------------------------------------|
| `color`        | The piece's color in PGN format            |
| `identity`     | The piece's identity in PGN format         |
| `position`     | The piece's position on the board          |
| `moves`        | The piece's possible moves                 |

The following code:

```php
<?php

$game = new Game;

$piece = $game->piece('b8');
```

Will generate this `$piece` object:

    stdClass Object
    (
        [color] => b
        [identity] => N
        [position] => b8
        [moves] => Array
            (
                [0] => a6
                [1] => c6
            )

    )

The piece's properties can be easily accessed this way:

```php
<?php

$piece->color;
$piece->identity;
$piece->position;
$piece->moves;
```

#### `pieces()`

Gets the pieces on the board by color.

    $blackPieces = $game->pieces('b');

`$blackPieces` is an array of PHP objects containing information about black pieces.

| Property       | Description                                |
|----------------|--------------------------------------------|
| `identity`     | The piece's identity in PGN format         |
| `position`     | The piece's position on the board          |
| `moves`        | The piece's possible moves                 |

The following code:

```php
<?php

$game = new Game;

$blackPieces = $game->pieces('b');
```

Will generate this `$blackPieces` array of objects:

    Array
    (
        [0] => stdClass Object
            (
                [identity] => R
                [position] => a8
                [moves] => Array
                    (
                    )

            )

        [1] => stdClass Object
            (
                [identity] => N
                [position] => b8
                [moves] => Array
                    (
                        [0] => a6
                        [1] => c6
                    )

            )

        [2] => stdClass Object
            (
                [identity] => B
                [position] => c8
                [moves] => Array
                    (
                    )

            )

        [3] => stdClass Object
            (
                [identity] => Q
                [position] => d8
                [moves] => Array
                    (
                    )

            )

        [4] => stdClass Object
            (
                [identity] => K
                [position] => e8
                [moves] => Array
                    (
                    )

            )

        [5] => stdClass Object
            (
                [identity] => B
                [position] => f8
                [moves] => Array
                    (
                    )

            )

        [6] => stdClass Object
            (
                [identity] => N
                [position] => g8
                [moves] => Array
                    (
                        [0] => f6
                        [1] => h6
                    )

            )

        [7] => stdClass Object
            (
                [identity] => R
                [position] => h8
                [moves] => Array
                    (
                    )

            )

        [8] => stdClass Object
            (
                [identity] => P
                [position] => a7
                [moves] => Array
                    (
                        [0] => a6
                        [1] => a5
                    )

            )

        [9] => stdClass Object
            (
                [identity] => P
                [position] => b7
                [moves] => Array
                    (
                        [0] => b6
                        [1] => b5
                    )

            )

        [10] => stdClass Object
            (
                [identity] => P
                [position] => c7
                [moves] => Array
                    (
                        [0] => c6
                        [1] => c5
                    )

            )

        [11] => stdClass Object
            (
                [identity] => P
                [position] => d7
                [moves] => Array
                    (
                        [0] => d6
                        [1] => d5
                    )

            )

        [12] => stdClass Object
            (
                [identity] => P
                [position] => e7
                [moves] => Array
                    (
                        [0] => e6
                        [1] => e5
                    )

            )

        [13] => stdClass Object
            (
                [identity] => P
                [position] => f7
                [moves] => Array
                    (
                        [0] => f6
                        [1] => f5
                    )

            )

        [14] => stdClass Object
            (
                [identity] => P
                [position] => g7
                [moves] => Array
                    (
                        [0] => g6
                        [1] => g5
                    )

            )

        [15] => stdClass Object
            (
                [identity] => P
                [position] => h7
                [moves] => Array
                    (
                        [0] => h6
                        [1] => h5
                    )

            )

    )

Pieces' properties can be easily accessed this way:

```php
<?php

$blackPieces[1]->identity;
$blackPieces[1]->position;
$blackPieces[1]->moves;
```

#### `history()`

Gets the game's history.

    $history = $game->history();

The following sequence of moves:

```php
<?php

$game = new Game;

$game->play('w', 'd4');
$game->play('b', 'c6');
$game->play('w', 'Bf4');
$game->play('b', 'd5');
$game->play('w', 'Nc3');
$game->play('b', 'Nf6');
$game->play('w', 'Bxb8');
$game->play('b', 'Rxb8');

$history = $game->history();
```

Will generate this `$history` array:

    Array
    (
    [0] => stdClass Object
        (
            [pgn] => d4
            [color] => w
            [identity] => P
            [position] => d2
            [isCapture] =>
            [isCheck] =>
        )

    [1] => stdClass Object
        (
            [pgn] => c6
            [color] => b
            [identity] => P
            [position] => c7
            [isCapture] =>
            [isCheck] =>
        )

    [2] => stdClass Object
        (
            [pgn] => Bf4
            [color] => w
            [identity] => B
            [position] => c1
            [isCapture] =>
            [isCheck] =>
        )

    [3] => stdClass Object
        (
            [pgn] => d5
            [color] => b
            [identity] => P
            [position] => d7
            [isCapture] =>
            [isCheck] =>
        )

    [4] => stdClass Object
        (
            [pgn] => Nc3
            [color] => w
            [identity] => N
            [position] => b1
            [isCapture] =>
            [isCheck] =>
        )

    [5] => stdClass Object
        (
            [pgn] => Nf6
            [color] => b
            [identity] => N
            [position] => g8
            [isCapture] =>
            [isCheck] =>
        )

    [6] => stdClass Object
        (
            [pgn] => Bxb8
            [color] => w
            [identity] => B
            [position] => f4
            [isCapture] => 1
            [isCheck] =>
        )

    [7] => stdClass Object
        (
            [pgn] => Rxb8
            [color] => b
            [identity] => R
            [position] => a8
            [isCapture] => 1
            [isCheck] =>
        )

    )

#### `captures()`

Gets the pieces captured by both players.

    $captures = $game->captures();

The following sequence of moves:

```php
<?php

$game = new Game;

$game->play('w', 'd4');
$game->play('b', 'c6');
$game->play('w', 'Bf4');
$game->play('b', 'd5');
$game->play('w', 'Nc3');
$game->play('b', 'Nf6');
$game->play('w', 'Bxb8');
$game->play('b', 'Rxb8');

$captures = $game->captures();
```

Will generate this `$captures` array:

    stdClass Object
    (
        [w] => Array
            (
                [0] => stdClass Object
                    (
                        [capturing] => stdClass Object
                            (
                                [identity] => B
                                [position] => f4
                            )

                        [captured] => stdClass Object
                            (
                                [identity] => N
                                [position] => b8
                            )

                    )

            )

        [b] => Array
            (
                [0] => stdClass Object
                    (
                        [capturing] => stdClass Object
                            (
                                [identity] => R
                                [position] => a8
                                [type] => castling long
                            )

                        [captured] => stdClass Object
                            (
                                [identity] => B
                                [position] => b8
                            )

                    )

            )

    )

#### `metadata()`

Fetches from the database random metadata of the current game.

    $metadata = $game->metadata();

The following sequence of moves:

```php
<?php

$game = new Game;

$game->play('w', 'd4');
$game->play('b', 'd5');
$game->play('w', 'Bf4');

$metadata = $game->metadata();
```

Might return a `$metadata` array as the described below.

    Array
    (
        [Event] => 11. KIIT Elite Open 2018
        [Site] => Bhubaneswar IND
        [Date] => 2018.05.28
        [Round] => 6.5
        [White] => Kravtsiv, Martyn
        [Black] => Das, Sayantan
        [Result] => 1-0
        [WhiteElo] => 2655
        [BlackElo] => 2437
        [EventDate] => 2018.05.25
        [ECO] => D02
        [movetext] => 1.d4 d5 2.Bf4 Nf6 3.Nf3 c5 4.e3 Nc6 5.Nbd2 e6 6.c3 Bd6 7.Bg3 O-O 8.Bd3 b6 9.e4 dxe4 10.Nxe4 Be7 11.Nxf6+ Bxf6 12.dxc5 bxc5 13.Qc2 h6 14.h4 Qe7 15.O-O-O Rd8 16.Bh7+ Kh8 17.Rxd8+ Nxd8 18.Be4 Bb7 19.Rd1 Rc8 20.Qa4 Bxe4 21.Qxe4 Nc6 22.Bd6 Qe8 23.Bxc5 Na5 24.Qb4 Nc6 25.Qa4 e5 26.Qe4 Na5 27.Rd5 Qb5 28.Bb4 Qf1+ 29.Rd1 Qb5 30.Bxa5 Qxa5 31.a3 Qb5 32.g3 Rc4 33.Qd5 Qb3 34.Nxe5 Rxc3+ 35.bxc3 Qxc3+ 36.Kb1 Bxe5 37.Qd8+ Kh7 38.Qd3+ Qxd3+ 39.Rxd3 Kg6 40.Kc2 Bc7 41.Rd5 Bb6 42.f3 h5 43.Kd3 f6 44.Ke2 Bc7 45.g4 hxg4 46.fxg4 Bb6 47.a4 1-0
    )

The random `$metadata` will vary in subsequent calls according to the chess games stored in the particular database.

    Array
    (
        [Event] => 1. Longtou Cup 2018
        [Site] => Qinhuangdao CHN
        [Date] => 2018.05.28
        [Round] => 3.2
        [White] => Antipov, Mikhail Al
        [Black] => Dai, Changren
        [Result] => 1/2-1/2
        [WhiteElo] => 2597
        [BlackElo] => 2436
        [EventDate] => 2018.05.26
        [ECO] => D00
        [movetext] => 1.d4 d5 2.Bf4 Nf6 3.e3 e6 4.Nd2 c5 5.c3 Nc6 6.Ngf3 Bd6 7.Bg3 O-O 8.Bb5 h6 9.Qe2 Bxg3 10.hxg3 Qb6 11.Rb1 Bd7 12.Bd3 Ng4 13.Nh4 f5 14.f3 Nf6 15.Ng6 Rfe8 16.f4 Kf7 17.Ne5+ Nxe5 18.fxe5 Ng4 19.Nf3 Qd8 20.Nh2 Nxh2 21.Rxh2 Qg5 22.Kf2 Kg8 23.Rh5 Qg6 24.dxc5 Rec8 25.Rbh1 Rxc5 26.R1h4 Rf8 27.g4 fxg4+ 28.Kg1 Bb5 29.Bxb5 Rxb5 30.Rxh6 Qb1+ 31.Kh2 1/2-1/2
    )

For further information on how to seed the PGN Chess database read the section [Command Line Interface (CLI)](https://pgn-chess.readthedocs.io/en/latest/command-line-interface/).
