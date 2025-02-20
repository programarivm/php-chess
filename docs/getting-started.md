# Getting Started

✨ Some familiarity with chess terms and concepts is required but if you're new to chess this tutorial will guide you through how to easily create amazing apps with PHP Chess. Happy coding and learning!

The [Chess\Variant\Classical\Board](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Variant/Classical/BoardTest.php) class is the easiest way to get started with PHP Chess.

```php
use Chess\Variant\Classical\Board;

$board = new Board();
```

If you have ever attended a chess tournament, you've probably noticed that each player writes down their move in PGN format on a piece of paper. PGN stands for Portable Game Notation and is a human-readable format that allows chess players to read and write chess games. When it comes to computer chess, though, a more appropriate machine-readable format called Long Algebraic Notation (LAN) is often used instead.

Be that as it may, you're already set up to play classical chess either in PGN or LAN format.

In PGN format:

```php
$board->play('w', 'e4');
```

In LAN format:

```php
$board->playLan('w', 'e2e4');
```

Every time a move is made, the state of the board changes.

```php
var_dump($board->toFen());
```

```text
string(55) "rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3"
```

This is the chess position in Forsyth–Edwards Notation (FEN) format after 1.e4.

🎉 Congrats! 1.e4 is one of the best moves to start with.

## Portable Game Notation (PGN)

✨ Portable Game Notation is a human-readable text format that allows chess players to read and write chess games.

PGN is convenient for when reading chess games annotated by humans, for example, those ones available in online databases or published in chess websites.

> 1.e4 e5 2.Nf3 Nf6 3.d4 Nxe4 4.Bd3 d5 5.Nxe5 Nd7 6.Nxd7 Bxd7 7.Nd2 Nxd2 8.Bxd2 Bd6 9.O-O h5 10.Qe1+ Kf8 11.Bb4 Qe7 12.Bxd6 Qxd6 13.Qd2 Re8 14.Rae1 Rh6 15.Qg5 c6 16.Rxe8+ Bxe8 17.Re1 Qf6 18.Qe3 Bd7 19.h3 h4 20.c4 dxc4 21.Bxc4 b5 22.Qa3+ Kg8 23.Qxa7 Qd8 24.Bb3 Rd6 25.Re4 Be6 26.Bxe6 Rxe6 27.Rxe6 fxe6 28.Qc5 Qa5 29.Qxc6 Qe1+ 30.Kh2 Qxf2 31.Qxe6+ Kh7 32.Qe4+ Kg8 33.b3 Qxa2 34.Qe8+ Kh7 35.Qxb5 Qf2 36.Qe5 Qb2 37.Qe4+ Kg8 38.Qd3 Qf2 39.Qc3 Qf4+ 40.Kg1 Kh7 41.Qd3+ g6 42.Qd1 Qe3+ 43.Kh1 g5 44.d5 g4 45.hxg4 h3 46.Qf3 1–0

World Chess Championship 2021. (2023, July 3). In Wikipedia. [https://en.wikipedia.org/wiki/World_Chess_Championship_2021](https://en.wikipedia.org/wiki/World_Chess_Championship_2021)

As you may probably know, a chess opening is the group of initial moves of a chess game. This definition applies to classical chess, however, there is no such thing as a chess opening in either Capablanca chess or Chess960. Those two variants were originally conceived to minimize memorization, so when it comes to chess openings it is assumed that we're in the realms of classical chess. ECO, which stands for Encyclopaedia of Chess Openings, is a standard classification system for chess openings.

Having said all that, let's now look at B54 which is the ECO code for "Sicilian Defense: Modern Variations, Main Line".

```php
use Chess\Variant\Classical\Board;

$board = new Board();
$board->play('w', 'e4');
$board->play('b', 'c5');
$board->play('w', 'Nf3');
$board->play('b', 'd6');
$board->play('w', 'd4');
$board->play('b', 'cxd4');
$board->play('w', 'Nxd4');

echo $board->toString();
```

```text
r  n  b  q  k  b  n  r
p  p  .  .  p  p  p  p
.  .  .  p  .  .  .  .
.  .  .  .  .  .  .  .
.  .  .  N  P  .  .  .
.  .  .  .  .  .  .  .
P  P  P  .  .  P  P  P
R  N  B  Q  K  B  .  R
```

The `play()` method in the [Chess\Variant\Classical\Board](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Variant/Classical/BoardTest.php) class allows to make moves in PGN format. So far so good, but if you're new to chess you may well play a wrong move in the Sicilian Defense: 4...Na6.

```php
$board->play('b', 'Na6');

echo $board->toString();
```

```text
r  .  b  q  k  b  n  r
p  p  .  .  p  p  p  p
n  .  .  p  .  .  .  .
.  .  .  .  .  .  .  .
.  .  .  N  P  .  .  .
.  .  .  .  .  .  .  .
P  P  P  .  .  P  P  P
R  N  B  Q  K  B  .  R
```

No worries! The `undo()` method comes to the rescue to take back a move.

```php
$board->undo();
$board->play('b', 'Nf6');

echo $board->movetext();
```

```text
1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6
```

The `movetext()` method is used to obtain the moves played in the game using Standard Algebraic Notation. Let's continue practicing chess openings. Now, what if you want to play a bunch of moves at once instead of one by one as in the previous example? [Chess\Play\SanPlay](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Play/SanPlayTest.php) allows to easily do so.

```php
use Chess\Play\SanPlay;

$movetext = '1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6';

$board = (new SanPlay($movetext))->validate()->board;

echo $board->toString();
```

```text
r  n  b  q  k  b  .  r
p  p  .  .  p  p  p  p
.  .  .  p  .  n  .  .
.  .  .  .  .  .  .  .
.  .  .  N  P  .  .  .
.  .  .  .  .  .  .  .
P  P  P  .  .  P  P  P
R  N  B  Q  K  B  .  R
```

Please note that the `validate()` method will throw an exception if the movetext is not valid. Once the `$board` object is successfully created, the game can be continued from that particular position.

```php
$board->play('w', 'Bb5+');

echo $board->toString();
```

```text
r  n  b  q  k  b  .  r
p  p  .  .  p  p  p  p
.  .  .  p  .  n  .  .
.  B  .  .  .  .  .  .
.  .  .  N  P  .  .  .
.  .  .  .  .  .  .  .
P  P  P  .  .  P  P  P
R  N  B  Q  K  .  .  R
```

Every time a move is made, the state of the board changes.

[Chess\Variant\Classical\Board](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Variant/Classical/BoardTest.php) provides you with plenty of methods to interact with a chess board object. It is a quite common use case to query the board state. As previously discussed, you may want to check out the corresponding tests for further details on how to use it. The unit tests are the best documentation. They contain hundreds of real examples on how to use the PHP Chess library.

The `isCheck()` method will confirm that the white king is in check after 5.Bb5+ while `isMate()` will confirm that it has not been mated.

```php
var_dump($board->isCheck());
```

```text
bool(true)
```

```php
var_dump($board->isMate());
```

```text
bool(false)
```

Similarly, you may want to know if the current position is a draw. The `isStalemate()` method is to find out if the current position is a stalemate while `isFivefoldRepetition()` will confirm if the game is drawn because of a fivefold repetition.

```php
var_dump($board->isStalemate());
```

```text
bool(false)
```

```php
var_dump($board->isFivefoldRepetition());
```

```text
bool(false)
```

Text comments in curly brackets can optionally be used in SAN movetexts as well as [Numeric Annotation Glyphs](https://en.wikipedia.org/wiki/Numeric_Annotation_Glyphs). The example below shows how [Chess\Play\SanPlay](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Play/SanPlayTest.php) is used to validate a SAN movetext that contains NAGs. Remember, the `validate()` method will throw an exception if the movetext is not valid.

```php
use Chess\Play\SanPlay;

$movetext = '1.e4 c5 2.Nf3 $1 d6 3.d4 cxd4 4.Nxd4 $48 Nf6 $113';

$sanPlay = (new SanPlay($movetext))->validate();

echo $sanPlay->sanMovetext->filtered();
```

```text
1.e4 c5 2.Nf3 $1 d6 3.d4 cxd4 4.Nxd4 $48 Nf6 $113
```

Comments and NAGs can be removed by passing the `false` value to the first and second arguments of the `filtered()` method.

```php
echo $sanPlay->sanMovetext->filtered($comments = true, $nags = false);
```

```text
1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6
```

🎉 Next, let's learn how to process chess moves from a graphical user interface.

## Long Algebraic Notation (LAN)

✨ The UCI protocol enables chess engines to communicate with user interfaces (UI) using Long Algebraic Notation (LAN) for moves.

```php
use Chess\Variant\Classical\Board;

$board = new Board();
$board->playLan('w', 'e2e4');
$board->playLan('b', 'c7c5');
$board->playLan('w', 'g1f3');
$board->playLan('b', 'd7d6');
$board->playLan('w', 'd2d4');
$board->playLan('b', 'c5d4');
$board->playLan('w', 'f3d4');
$board->playLan('b', 'g8f6');

echo $board->movetext();
```

```text
1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6
```

[Chess\Play\LanPlay](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Play/LanPlayTest.php) allows to play a bunch of LAN moves at once instead of one by one.

```php
use Chess\Play\LanPlay;

$movetext = '1.e2e4 c7c5 2.g1f3 d7d6 3.d2d4 c5d4 4.f3d4 g8f6';

$board = (new LanPlay($movetext))->validate()->board;

echo $board->toString();
```

```text
r  n  b  q  k  b  .  r
p  p  .  .  p  p  p  p
.  .  .  p  .  n  .  .
.  .  .  .  .  .  .  .
.  .  .  N  P  .  .  .
.  .  .  .  .  .  .  .
P  P  P  .  .  P  P  P
R  N  B  Q  K  B  .  R
```

🎉 And, it's easy!

## Recursive Annotation Variation (RAV)

✨ RAV stands for Recursive Annotation Variation. It is an extension of the Standard Algebaric Notation (SAN) format that allows to annotate chess variations. This format is especially useful for tutorials, notable games, chess studies and so on.

Comments are enclosed in curly brackets. Variations are enclosed in parentheses which can be nested recursively as many times as required with the trait that the previous move may need to be undone in order to play a variation.

The example below describes how to play the Open Sicilian.

> 1.e4 c5 {enters the Sicilian Defense, the most popular and best-scoring response to White's first move.} (2.Nf3 {is played in about 80% of Master-level games after which there are three main options for Black.} (2... Nc6) (2... e6) (2... d6 {is Black's most common move.} 3.d4 {lines are collectively known as the Open Sicilian.} cxd4 4.Nxd4 Nf6 5.Nc3 {allows Black choose between four major variations: the Najdorf, Dragon, Classical and Scheveningen.} (5...a6 {is played in the Najdorf variation.}) (5...g6 {is played in the Dragon variation.}) (5...Nc6 {is played in the Classical variation.}) (5...e6 {is played in the Scheveningen variation.})))

Sicilian Defense. (2023, July 2). In Wikipedia. [https://en.wikipedia.org/wiki/Sicilian_Defence](https://en.wikipedia.org/wiki/Sicilian_Defence)

![Figure 1](https://raw.githubusercontent.com/chesslablab/php-chess/main/docs/getting_started_01.png)

The RAV reader above displays the variation levels in different shades of gray. It is a 2D scrollable HTML table where the main line is shown in a white background color. The deeper the level, the darker the background color.

[Chess\Play\RavPlay](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Play/RavPlayTest.php) allows to play an RAV.

```php
use Chess\Play\RavPlay;

$movetext = "1.e4 c5
    (2.Nf3 (2... Nc6) (2... e6)
        (2... d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3
            (5...a6)
            (5...g6)
            (5...Nc6)
            (5...e6)
        )
    )";

$ravPlay = (new RavPlay($movetext))->validate();

print_r($ravPlay->fen);
```

```text
Array
(
    [0] => rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -
    [1] => rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3
    [2] => rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq c6
    [3] => rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -
    [4] => r1bqkbnr/pp1ppppp/2n5/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -
    [5] => rnbqkbnr/pp1p1ppp/4p3/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -
    [6] => rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -
    [7] => rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq d3
    [8] => rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -
    [9] => rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -
    [10] => rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -
    [11] => rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -
    [12] => rnbqkb1r/1p2pppp/p2p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -
    [13] => rnbqkb1r/pp2pp1p/3p1np1/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -
    [14] => r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -
    [15] => rnbqkb1r/pp3ppp/3ppn2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -
)
```

The FEN history is retrieved as an unidimensional array so it can be easily consumed by a frontend UI as shown in Figure 1.

The movetext will also pass the validation if adding comments and NAGs.

```php
use Chess\Play\RavPlay;

$movetext = "1.e4 c5 {enters the Sicilian Defense.}
    (2.Nf3 (2... Nc6) (2... e6)
        (2... d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3
            (5...a6 {is played in the Najdorf variation.})
            (5...g6 {is played in the Dragon variation.})
            (5...Nc6 {is played in the Classical variation.})
            (5...e6 {is played in the Scheveningen variation.})
        )
    )";

$ravPlay = (new RavPlay($movetext))->validate();

print_r($ravPlay->fen);
```

```text
Array
(
    [0] => rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -
    [1] => rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3
    [2] => rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq c6
    [3] => rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -
    [4] => r1bqkbnr/pp1ppppp/2n5/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -
    [5] => rnbqkbnr/pp1p1ppp/4p3/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -
    [6] => rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -
    [7] => rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq d3
    [8] => rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -
    [9] => rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -
    [10] => rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -
    [11] => rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -
    [12] => rnbqkb1r/1p2pppp/p2p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -
    [13] => rnbqkb1r/pp2pp1p/3p1np1/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -
    [14] => r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -
    [15] => rnbqkb1r/pp3ppp/3ppn2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -
)
```

This is how to obtain the validated movetext.

```php
$movetext = $ravPlay->ravMovetext->movetext;
```

The `filtered()` method is to remove tabs and spaces.

```php
$movetext = $ravPlay->ravMovetext->filtered();

echo $movetext;
```

```text
1.e4 c5 {enters the Sicilian Defense, the most popular and best-scoring response to White's first move.} (2.Nf3 {is played in about 80% of Master-level games after which there are three main options for Black.} (2...Nc6) (2...e6) (2...d6 {is Black's most common move.} 3.d4 {lines are collectively known as the Open Sicilian.} cxd4 4.Nxd4 Nf6 5.Nc3 {allows Black choose between four major variations: the Najdorf, Dragon, Classical and Scheveningen.} (5...a6 {is played in the Najdorf variation.}) (5...g6 {is played in the Dragon variation.}) (5...Nc6 {is played in the Classical variation.}) (5...e6 {is played in the Scheveningen variation.})))
```

Comments and NAGs can be removed by passing the `false` value to the first and second arguments of the `filtered()` method.

```php
$movetext = $ravPlay->ravMovetext->filtered($comments = false);

echo $movetext;
```

```text
1.e4 c5 (2.Nf3 (2...Nc6) (2...e6) (2...d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 (5...a6) (5...g6) (5...Nc6) (5...e6)))
```

An RAV can be started from a particular FEN if passing a chess board object into the constructor of the class. As you can see in the example below, [Chess\Variant\Classical\FenToBoardFactory](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Variant/Classical/FenToBoardFactoryTest.php) is used for this purpose.

```php
use Chess\Play\RavPlay;
use Chess\Variant\Classical\FenToBoardFactory;

$movetext = "1.Ra7 Kg8 2.Kg2 Kf8 3.Kf3 Ke8 4.Ke4 Kd8 5.Kd5 Kc8
    (5...Ke8 6.Kd6 Kf8 7.Ke6 Kg8 8.Kf6 Kh8 9.Kg6 Kg8 10.Ra8#)
    6.Kd6 Kb8
    (6...Kd8 7.Ra8#)
    7.Rc7 Ka8 8.Kc6 Kb8 9.Kb6 Ka8 10.Rc8#";

$board = FenToBoardFactory::create('7k/8/8/8/8/8/8/R6K w - -');

$ravPlay = (new RavPlay($movetext, $board))->validate();

print_r($ravPlay->fen);
```

```text
Array
(
    [0] => 7k/8/8/8/8/8/8/R6K w - -
    [1] => 7k/R7/8/8/8/8/8/7K b - -
    [2] => 6k1/R7/8/8/8/8/8/7K w - -
    [3] => 6k1/R7/8/8/8/8/6K1/8 b - -
    [4] => 5k2/R7/8/8/8/8/6K1/8 w - -
    [5] => 5k2/R7/8/8/8/5K2/8/8 b - -
    [6] => 4k3/R7/8/8/8/5K2/8/8 w - -
    [7] => 4k3/R7/8/8/4K3/8/8/8 b - -
    [8] => 3k4/R7/8/8/4K3/8/8/8 w - -
    [9] => 3k4/R7/8/3K4/8/8/8/8 b - -
    [10] => 2k5/R7/8/3K4/8/8/8/8 w - -
    [11] => 4k3/R7/8/3K4/8/8/8/8 w - -
    [12] => 4k3/R7/3K4/8/8/8/8/8 b - -
    [13] => 5k2/R7/3K4/8/8/8/8/8 w - -
    [14] => 5k2/R7/4K3/8/8/8/8/8 b - -
    [15] => 6k1/R7/4K3/8/8/8/8/8 w - -
    [16] => 6k1/R7/5K2/8/8/8/8/8 b - -
    [17] => 7k/R7/5K2/8/8/8/8/8 w - -
    [18] => 7k/R7/6K1/8/8/8/8/8 b - -
    [19] => 6k1/R7/6K1/8/8/8/8/8 w - -
    [20] => R5k1/8/6K1/8/8/8/8/8 b - -
    [21] => 2k5/R7/3K4/8/8/8/8/8 b - -
    [22] => 1k6/R7/3K4/8/8/8/8/8 w - -
    [23] => 3k4/R7/3K4/8/8/8/8/8 w - -
    [24] => R2k4/8/3K4/8/8/8/8/8 b - -
    [25] => 1k6/2R5/3K4/8/8/8/8/8 b - -
    [26] => k7/2R5/3K4/8/8/8/8/8 w - -
    [27] => k7/2R5/2K5/8/8/8/8/8 b - -
    [28] => 1k6/2R5/2K5/8/8/8/8/8 w - -
    [29] => 1k6/2R5/1K6/8/8/8/8/8 b - -
    [30] => k7/2R5/1K6/8/8/8/8/8 w - -
    [31] => k1R5/8/1K6/8/8/8/8/8 b - -
)
```

🎉 So this is amazing! That's all we need to read and write chess tutorials, guides and how-tos.
