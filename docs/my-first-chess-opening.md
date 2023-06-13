# My First Chess Opening

Almost everything in PHP Chess can be done by instantiating a chessboard object. At present there are three different variants available and the default one is classical chess.

| Variant | Namespace |
| ------- | ---------- |
| Capablanca | `Chess\Variant\Capablanca\Board` |
| Chess960 | `Chess\Variant\Chess960\Board` |
| Classical | `Chess\Variant\Classical\Board` |

So when it comes to chess openings, it is assumed that we're in the realms of classical chess as well. Both Capablanca and Chess960 were originally conceived to minimize memorization.

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

As discussed in the previous section, the PGN format is convenient for when reading chess games annotated by humans, for example, those ones available in online databases or published in chess websites.

> 1. d4 Nf6 2. c4 e6 3. Nf3 d5 4. h3 dxc4 5. e3 c5 6. Bxc4 a6 7. 0–0 Nc6 8. Nc3 b5 9. Bd3 Bb7 10. a4 b4 11. Ne4 Na5 (diagram) 12. Nxf6+ gxf6 13. e4 c4 14. Bc2 Qc7 15. Bd2 Rg8 16. Rc1 0-0-0 17. Bd3 Kb8 18. Re1 f5 19. Bc2 Nc6 20. Bg5 Rxg5 21. Nxg5 Nxd4 22. Qh5 f6 23. Nf3 Nxc2 24. Rxc2 Bxe4 25. Rd2 Bd6 26. Kh1 c3 27. bxc3 bxc3 28. Rd4 c2 29. Qh6 e5 0–1

The example above is a game from [World Chess Championship 2023](https://en.wikipedia.org/wiki/World_Chess_Championship_2023) published in Wikipedia.

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

No worries! We've all been there.

The `undo()` method comes to the rescue to fix mistakes like this one.

```php
$board = $board->undo();
$board->play('b', 'Nf6');

echo $board->getMovetext();
```

```
1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6
```

Remember, if reading the main line of the Sicilian Defense from a JavaScript application, you may want to use the LAN format rather than the PGN format. Chances are that the JavaScript chessboard will use the LAN format for move generation.

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

echo $board->getMovetext();
```

```
1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6
```
