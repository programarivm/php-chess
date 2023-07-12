# Read PGN

✨ Portable Game Notation is a human-readable text format that allows chess players to read and write chess games.

There are three different variants supported with the default one being classical chess.

| Variant | Chessboard |
| ------- | ---------- |
| Capablanca | [Chess\Variant\Capablanca\Board](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Variant/Capablanca/BoardTest.php) |
| Chess960 | [Chess\Variant\Chess960\Board](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Variant/Chess960/BoardTest.php) |
| Classical | [Chess\Variant\Classical\Board](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Variant/Classical/BoardTest.php) |

Both Capablanca and Chess960 were originally conceived to minimize memorization. So when it comes to chess openings, it is assumed that we're in the realms of classical chess as well. Let's now have a look at B54 which is the ECO code for "Sicilian Defense: Modern Variations, Main Line".

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

echo $board->toAsciiString();
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

As discussed in [Getting Started](https://php-chess.readthedocs.io/en/latest/getting-started/), the PGN format is convenient for when reading chess games annotated by humans, for example, those ones available in online databases or published in chess websites.

> 1.e4 e5 2.Nf3 Nf6 3.d4 Nxe4 4.Bd3 d5 5.Nxe5 Nd7 6.Nxd7 Bxd7 7.Nd2 Nxd2 8.Bxd2 Bd6 9.O-O h5 10.Qe1+ Kf8 11.Bb4 Qe7 12.Bxd6 Qxd6 13.Qd2 Re8 14.Rae1 Rh6 15.Qg5 c6 16.Rxe8+ Bxe8 17.Re1 Qf6 18.Qe3 Bd7 19.h3 h4 20.c4 dxc4 21.Bxc4 b5 22.Qa3+ Kg8 23.Qxa7 Qd8 24.Bb3 Rd6 25.Re4 Be6 26.Bxe6 Rxe6 27.Rxe6 fxe6 28.Qc5 Qa5 29.Qxc6 Qe1+ 30.Kh2 Qxf2 31.Qxe6+ Kh7 32.Qe4+ Kg8 33.b3 Qxa2 34.Qe8+ Kh7 35.Qxb5 Qf2 36.Qe5 Qb2 37.Qe4+ Kg8 38.Qd3 Qf2 39.Qc3 Qf4+ 40.Kg1 Kh7 41.Qd3+ g6 42.Qd1 Qe3+ 43.Kh1 g5 44.d5 g4 45.hxg4 h3 46.Qf3 1–0

World Chess Championship 2021. (2023, July 3). In Wikipedia. [https://en.wikipedia.org/wiki/World_Chess_Championship_2021](https://en.wikipedia.org/wiki/World_Chess_Championship_2021)

So far so good, but if you're new to chess you may well play a wrong move in the Sicilian Defense: 4...Na6.

```php
$board->play('b', 'Na6');

echo $board->toAsciiString();
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

No worries! We've all been there. The `undo()` method comes to the rescue to fix mistakes like this one.

```php
$board = $board->undo();
$board->play('b', 'Nf6');

echo $board->getMovetext();
```

```text
1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6
```

Now, what if you want to play a bunch of PGN moves at once instead of one by one as in the previous example? This is a common use case, and [Chess\Play\SanPlay](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Play/SanPlayTest.php) allows to easily do so. As it name implies, this class is intended to play a Standard Algebaric Notation (SAN) movetext. The validate() method will throw a Chess\Exception\PlayException if the movetext is not valid.

```php
use Chess\Play\SanPlay;

$movetext = '1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6';

$board = (new SanPlay($movetext))
    ->validate()
    ->getBoard();

echo $board->toAsciiString();
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

The game can be continued from this position — remember, almost everything in PHP Chess is done using a chessboard object.

```php
$board->play('w', 'Bb5+');

echo $board->toAsciiString();
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

Every time a move is made, the state of the board changes and now the white king is in check.

```php
var_dump($board->isCheck());
```

```text
bool(true)
```

Of course the king is not mated in this position.

```php
var_dump($board->isMate());
```

```text
bool(false)
```

Also it is not stalemated.

```php
var_dump($board->isStalemate());
```

```text
bool(false)
```

And it is not a fivefold repetition yet.

```php
var_dump($board->isFivefoldRepetition());
```

```text
bool(false)
```

Otherwise the game would end.

[Numeric Annotation Glyphs](https://en.wikipedia.org/wiki/Numeric_Annotation_Glyphs) (NAGs) can optionally be used in SAN movetexts, so this is how you'd typically validate a SAN movetext using NAGs for further processing. Remember, the validate() method will throw a Chess\Exception\PlayException if the movetext is not valid.

```php
use Chess\Play\SanPlay;

$movetext = '1.e4 c5 2.Nf3 $1 d6 3.d4 cxd4 4.Nxd4 $48 Nf6 $113';

$sanPlay = (new SanPlay($movetext))->validate();

echo $sanPlay->getSanMovetext()->filtered();
```

```text
1.e4 c5 2.Nf3 $1 d6 3.d4 cxd4 4.Nxd4 $48 Nf6 $113
```

NAGs can be removed by passing the false value to the filtered() method.

```php
echo $sanPlay->getSanMovetext()->filtered($nags = false);
```

```text
1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6
```

🎉 Next, let's learn how to process chess moves from a graphical user interface.
