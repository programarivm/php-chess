# Read PGN

📌 Portable Game Notation is a human-readable format that allows chess players to read and write chess games.

Almost everything in PHP Chess can be done with a chessboard object. There are three different variants supported with the default one being classical chess.

| Variant | Chessboard |
| ------- | ---------- |
| Capablanca | `Chess\Variant\Capablanca\Board` |
| Chess960 | `Chess\Variant\Chess960\Board` |
| Classical | `Chess\Variant\Classical\Board` |

Both Capablanca and Chess960 were originally conceived to minimize memorization. So when it comes to chess openings, it is assumed that we're in the realms of classical chess as well.

Let's now have a look at B54 which is the ECO code for "Sicilian Defense: Modern Variations, Main Line".

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

```
r  n  b  q  k  b  n  r
p  p  .  .  p  p  p  p
.  .  .  p  .  .  .  .
.  .  .  .  .  .  .  .
.  .  .  N  P  .  .  .
.  .  .  .  .  .  .  .
P  P  P  .  .  P  P  P
R  N  B  Q  K  B  .  R
```

As discussed in Section 2, Getting Started, the PGN format is convenient for when reading chess games annotated by humans, for example, those ones available in online databases or published in chess websites.

> 1. e4 e5 2. Nf3 Nf6 3. d4 Nxe4 4. Bd3 d5 5. Nxe5 Nd7 6. Nxd7 Bxd7 7. Nd2 Nxd2 8. Bxd2 Bd6 9. 0-0 h5 10. Qe1+ Kf8 11. Bb4 Qe7 12. Bxd6 Qxd6 13. Qd2 Re8 14. Rae1 Rh6 15. Qg5 c6 16. Rxe8+ Bxe8 17. Re1 Qf6 18. Qe3 Bd7 19. h3 h4 20. c4 dxc4 21. Bxc4 b5 (diagram) 22. Qa3+ Kg8 23. Qxa7 Qd8 24. Bb3 Rd6 25. Re4 Be6 26. Bxe6 Rxe6 27. Rxe6 fxe6 28. Qc5 Qa5 29. Qxc6 Qe1+ 30. Kh2 Qxf2 31. Qxe6+ Kh7 32. Qe4+ Kg8 33. b3 Qxa2 34. Qe8+ Kh7 35. Qxb5 Qf2 36. Qe5 Qb2 37. Qe4+ Kg8 38. Qd3 Qf2 39. Qc3 Qf4+ 40. Kg1 Kh7 41. Qd3+ g6 42. Qd1 Qe3+ 43. Kh1 g5 44. d5 g4 45. hxg4 h3 46. Qf3 1–0

The example above is a game from [World Chess Championship 2022](https://en.wikipedia.org/wiki/World_Chess_Championship_2021) published in Wikipedia.

So far so good, but if you're new to chess you may well play a wrong move in the Sicilian Defense: 4...Na6.

```php
$board->play('b', 'Na6');

echo $board->toAsciiString();
```

```
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

```
1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6
```

Now, what if you want to play a bunch of PGN moves at once instead of one by one as in the previous example? This is a common use case, and `Chess\Player\PgnPlayer` allows to easily do so. As it name implies, this class is intended to play a PGN movetext in string format.

```php
use Chess\Player\PgnPlayer;

$movetext = '1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6';

$board = (new PgnPlayer($movetext))
    ->play()
    ->getBoard();

echo $board->toAsciiString();
```

```
r  n  b  q  k  b  .  r
p  p  .  .  p  p  p  p
.  .  .  p  .  n  .  .
.  .  .  .  .  .  .  .
.  .  .  N  P  .  .  .
.  .  .  .  .  .  .  .
P  P  P  .  .  P  P  P
R  N  B  Q  K  B  .  R
```
