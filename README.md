## PGN Chess for PHP

[![Build Status](https://travis-ci.org/programarivm/pgn-chess.svg?branch=master)](https://travis-ci.org/programarivm/pgn-chess)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

![PGN Chess](/resources/chess-move.jpg?raw=true)

This is a simple, friendly, and powerful PGN (Portable Game Notation) library for running chess games from within PHP applications. It is a chess board representation that can be used in chess engines, chess applications and chess algorithms.

PGN Chess can play and validate PGN notated games, and comes to the rescue in the following scenarios:

- Develop APIs on the server side for validating chess games
- Create funny, random games for fun
- Analyze games of chess
- Validate games
- Build chess-related web apps
- Play chess moves

### 0. Example

Look at [React PGN Chess](https://github.com/programarivm/react-pgn-chess), this is a basic chess server that uses PGN Chess along with [Ratchet](http://socketo.me/) (PHP WebSockets).

### 1. Install

Via composer:

    $ composer require programarivm/pgn-chess

### 2. Instantiation

Just instantiate a game and play PGN moves:

```php
<?php

use PGNChess\Game;

$game = new Game;

$isLegalMove = $game->play('w', 'e4');
```
All action takes place in the `$game` object. The call to the `$board->play` method returns `true` or `false` depending on whether or not a chess move can be run on the board.

> **Side note**: For further information about how chess moves and PGN symbols are managed internally please look at the [PGN](https://github.com/programarivm/pgn-chess/tree/master/src/PGN) folder.

It is up to you how to process the moves accordingly -- go into a loop till the player runs a valid move, ask them to please try again, play a sound, exit the game or whatever thing you consider appropriate. The important thing is that PGN Chess understands chess rules, internally replicating the game being played on the board.

> **Side note**: PGN Chess games are actually run in the computer's memory. So, if it turns out that for whatever reason a player forgets to append the + symbol to their check moves, PGN Chess will anyway understand that it is a check move. The same thing goes for checkmate moves.

### 3. Game methods

#### 3.1. `isCheck()`

Finds out if the game is in check.

```php
$isCheck = $game->isCheck();
```

#### 3.2. `isMate()`

Finds out if the game is over.

```php
$isMate = $game->isMate();
```

#### 3.3. `status()`

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

#### 3.4. `piece()`

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

#### 3.5. `pieces()`

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

#### 3.6. `history()`

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

#### 3.7. `captures()`

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

### 4. Command Line Interface (CLI)

Make sure to have an `.env` file in your app's root folder:

    APP_ENV=dev

    DB_HOST=localhost
    DB_USER=root
    DB_PASSWORD=root
    DB_NAME=pgn_chess
    DB_PORT=3306

#### 4.1. `createdb.php`

Creates a MySQL PGN Chess database.

    php cli/createdb.php
    This will remove the current PGN Chess database and the data will be lost.
    Do you want to proceed? (Y/N): y

#### 4.2. `pgntomysql.php`

Converts a PGN file into a MySQL `INSERT` statement.

    php cli/pgntomysql.php example.pgn > example.sql

This is the output generated.

    INSERT INTO games (Event, Site, Date, Round, White, Black, Result, WhiteTitle, BlackTitle, WhiteElo, BlackElo, WhiteUSCF, BlackUSCF, WhiteNA, BlackNA, WhiteType, BlackType, EventDate, EventSponsor, Section, Stage, Board, Opening, Variation, SubVariation, ECO, NIC, Time, UTCTime, UTCDate, TimeControl, SetUp, FEN, Termination, Annotator, Mode, PlyCount, movetext) VALUES ('TCh-FRA Top 12 2018', 'Brest FRA', '2018.05.28', '3.3', 'Dornbusch, Tatiana', 'Feller, Sebastien', '0-1', null, null, '2290', '2574', null, null, null, null, null, null, '2018.05.26', null, null, null, null, null, null, null, 'E11', null, null, null, null, null, null, null, null, null, null, null, '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5 8.d5 e59.O-O Nbd7 10.b4 a5 11.b5 g6 12.Nb1 Nh5 13.Bh6 Ng7 14.Qc2 Nb6 15.Nbd2 Bd716.Kh1 Qc7 17.Rae1 Rae8 18.Ng1 Bc8 19.f4 exf4 20.Qc3 f6 21.Rxf4 Nd7 22.Bc2Ne5 23.h3 Nf7 24.Bxg7 Kxg7 25.Rf2 Kg8 26.Ngf3 Ne5 27.Ref1 Bd8 28.Kg1 Qg729.Nb3 b6 30.Nbd2 Qh6 31.Nxe5 fxe5 32.Rxf8+ Rxf8 33.Rxf8+ Kxf8 34.a4 Kg735.Bd1 Qh4 36.Kf1 Qf4+ 37.Bf3 h5 38.Ke2 Kh6 39.Qe3 g5 40.Qxf4 exf4 41.e5dxe5 42.d6 Be6 43.Bc6 g4 44.hxg4 hxg4 45.Kd3 Kg5 46.Ke4 Kf6 47.Be8 Bg8 48.Bd7 Be6 49.Bc6 Bf5+ 50.Kd5 f3 51.g3 f2 52.Be8 e4 53.Nf1 Be6+ 54.Kc6 Ke555.Bh5 Kd4 0-1'),('11. KIIT Elite Open 2018', 'Bhubaneswar IND', '2018.05.28', '5.3', 'Nitin, S', 'Amonatov, Farrukh', '0-1', null, null, '2432', '2608', null, null, null, null, null, null, '2018.05.25', null, null, null, null, null, null, null, 'B90', null, null, null, null, null, null, null, null, null, null, null, '1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 a6 6.Be3 e5 7.Nb3 Be7 8.h3 b59.a4 b4 10.Nd5 Nbd7 11.Nxe7 Kxe7 12.Qd2 a5 13.O-O-O Qc7 14.f3 Bb7 15.g4Rhc8 16.Bb5 Bc6 17.Bxc6 Qxc6 18.Bg5 Ra6 19.Qe2 Nb6 20.Bxf6+ gxf6 21.f4 Qc422.Rd3 Nxa4 23.g5 fxg5 24.fxe5 dxe5 25.Rhd1 Rac6 26.R1d2 Nc5 27.Nxc5 Qxc528.Qg4 Kf8 29.Rf3 Kg8 30.Qf5 Rf8 31.Rg3 Rg6 32.Rd5 Qc4 33.Rxg5 b3 34.Rd2bxc2 35.Rxc2 Qd4 36.Rcg2 Rd8 37.Rxg6+ hxg6 38.Rxg6+ Kf8 39.Qf3 Qa4 40.Rg2Qa1+ 41.Kc2 Rc8+ 42.Kd3 Qc1 43.Qf6 Qc4+ 44.Ke3 Qd4+ 45.Kf3 Qd3+ 46.Kg4Qxe4+ 47.Kg3 Qg6+ 48.Qxg6 fxg6 49.Kf3 Kf7 50.Ke4 Kf6 51.Rf2+ Ke6 52.Rg2Rc4+ 53.Ke3 Kf5 54.Rf2+ Rf4 55.Rc2 Rh4 56.Rf2+ Ke6 57.Rf3 Rb4 0-1');

#### 4.3. `pgnsyntax.php`

Checks if the syntax of a PGN file is valid.

    php pgnsyntax.php games.pgn 
    Whoops! Sorry but this is not a valid PGN file.
    --------------------------------------------------------
    Site: Bhubaneswar IND
    Date: 2018.05.28
    Round: 5.3
    White: Nitin, S
    Black: Amonatov, Farrukh
    Result: 0-1
    WhiteElo: 2432
    BlackElo: 2608
    EventDate: 2018.05.25
    ECO: B90
    --------------------------------------------------------
    Event: 11. KIIT Elite Open 2018
    Site: Bhubaneswar IND
    Date: 2018.05.28
    Round: 5.17
    White: Raahul, V S
    Black: Neverov, Valeriy
    Result: 1/2-1/2
    WhiteElo: 2231
    BlackElo: 2496
    EventDate: 2018.05.25
    ECO: A25
    1.foo f5 2.Nc3 Nf6 3.g3 e5 4.Bg2 Nc6 5.e3 Bb4 6.Nge2 O-O 7.O-O d6 8.Nd5 Nxd5 9.cxd5 Ne7 10.d4 Ba5 11.b4 Bb6 12.dxe5 dxe5 13.Qb3 Kh8 14.a4 a6 15. Bb2 Ng6 16.a5 Ba7 17.Qc3 Re8 18.Nf4 Re7 19.Nxg6+ hxg6 20.Rac1 Rb8 21.b5 b6 22.Ba3 Rf7 23.axb6 Rxb6 24.Bc5 e4 25.Bxb6 Bxb6 26.bxa6 Bxa6 27.Rfd1 Rd7 28.Qe5 Rd6 29.Bf1 Bxf1 30.Kxf1 c6 31.Kg2 Kh7 32.h4 cxd5 33.h5 Qd7 34.Rh1 g5 35.Rc8 f4 36.h6 f3+ 37.Kg1 Rxh6 38.Rh8+ Kg6 39.R1xh6+ gxh6 40.Rg8+ Kh5 41.Qf6 Bxe3 42.fxe3 Qc7 43.Qg6+ Kg4 44.Qe6+ Kxg3 45.Rc8 Qa7 46.Qd6+ Kg4 47.Qe6+ Kg3 48.Qd6+ Kg4 1/2-1/2
    --------------------------------------------------------
    Event: TCh-FRA Top 12 2018
    Site: Brest FRA
    Date: 2018.05.28
    Round: 3.6
    White: Eljanov, Pavel
    Black: Ragger, Markus
    Result: 1/2-1/2
    WhiteElo: 2702
    BlackElo: 2672
    EventDate: 2018.05.26
    ECO: A34
    1.Nf3 Nf6 20.c4 c5 3.Nc3 d5 4.cxd5 Nxd5 5.e3 e6 6.Bb5+ Bd7 7.Be2 Nc6 8.O-O Be7 9.d4 cxd4 10.Nxd5 exd5 11.exd4 O-O 12.Ne5 Bf5 13.Be3 Bf6 14.Nxc6 bxc6 15.Bd3 Bxd3 16.Qxd3 Qb6 17.Rfc1 Rfe8 18.Qc3 1/2-1/2
    --------------------------------------------------------
    Please check these games. Do they provide the STR (Seven Tag Roster)? Is the movetext valid?

### 5. License

The GNU General Public License.

### 6. Contributions

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "PGN Chess Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)
- Say hello on [Google+](https://plus.google.com/+Programarivm)

Many thanks.
