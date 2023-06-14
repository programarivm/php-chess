# Convert FEN

📌 Almost everything in PHP Chess can be done with a chessboard object.

FEN stands for Forsyth-Edwards Notation and is the standard way for describing chess positions using text strings. At some point you'll definitely want to convert a FEN string into a chessboard object for further processing, and this can be done according to the variants supported.

| Variant | FEN Converter | Chessboard |
| ------- | ------------- | ---------- |
| Capablanca | `Chess\Variant\Capablanca\FEN\StrToBoard` | `Chess\Variant\Capablanca\Board` |
| Chess960 | `Chess\Variant\Chess960\FEN\StrToBoard` | `Chess\Variant\Chess960\Board` |
| Classical | `Chess\Variant\Classical\FEN\StrToBoard` | `Chess\Variant\Classical\Board` |

Let's continue the game from the FEN position of B54, which is the ECO code for "Sicilian Defense: Modern Variations, Main Line" previously discussed in Section 3, Read PGN.

```php
use Chess\Variant\Classical\FEN\StrToBoard;

$board = (new StrToBoard('rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -'))
    ->create();

$board->play('w', 'Nc3');
$board->play('b', 'Nc6');

echo $board->toFen();
```

```
r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -
```

After 5.Nc3 Nc6, B54 turns into B56 which is the ECO code for "Sicilian Defense: Classical Variation".
