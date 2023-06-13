# Read LAN

📌 Computers and graphic user interfaces (GUI) often prefer an easy-to-use, machine-readable format called Long Algebraic Notation.

Remember, if reading the main line of the Sicilian Defense from a JavaScript application, you may want to use the LAN format rather than the PGN format. Chances are that the JavaScript chessboard will be using the LAN format for move generation.

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
