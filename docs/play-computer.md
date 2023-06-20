# Play Computer

✨ UCI engines not only allow to play chess with the computer but are also a helpful tool when analyzing chess games.

[Chess\UciEngine\Stockfish](https://github.com/chesslablab/php-chess/blob/master/tests/unit/UciEngine/StockfishTest.php) allows to play chess against the Stockfish chess engine using PHP, but first things first, make sure to install it on your computer.

```text
sudo apt-get install stockfish
```

Then, you're set up to play chess against Stockfish as described in the following example.

```php
use Chess\UciEngine\Stockfish;
use Chess\Variant\Classical\Board;

$board = new Board();
$board->play('w', 'e4');

$stockfish = (new Stockfish($board))
    ->setOptions([
        'Skill Level' => 9
    ])
    ->setParams([
        'depth' => 3
    ]);

$lan = $stockfish->play($board->toFen());

$board->playLan('b', $lan);
```

PHP Chess classes can be combined to do different things. For example, you may want to play against Stockfish from this FEN position published in your favorite online publication.

```php
use Chess\UciEngine\Stockfish;
use Chess\Variant\Classical\FEN\StrToBoard;

$board = (new StrToBoard('4k2r/pp1b1pp1/8/3pPp1p/P2P1P2/1P3N2/1qr3PP/R3QR1K w k -'))
    ->create();

$stockfish = (new Stockfish($board))
    ->setOptions([
        'Skill Level' => 20
    ])
    ->setParams([
        'depth' => 12
    ]);

$lan = $stockfish->play($board->toFen());

$board->playLan('w', $lan);

echo $board->getMovetext();
```

```text
1.Qb4
```

The FEN is converted to a chessboard object as described in [Convert FEN to Board](https://php-chess.readthedocs.io/en/latest/convert-fen-to-board/). The `Skill Level` is set to `20` and the depth is set to `12` in order to get a more accurate response from Stockfish.

The same thing goes for PGN annotated games. This is how to play against Stockfish after loading a SAN movetext into a chess board object.

```php
use Chess\Play\SAN;
use Chess\UciEngine\Stockfish;

$movetext = '1.d4 Nf6 2.c4 c5 3.d5 e6 4.Nc3 exd5 5.cxd5 d6 6.e4 g6 7.Nf3 Bg7';

$board = (new SAN($movetext))
    ->play()
    ->getBoard();

$stockfish = (new Stockfish($board))
    ->setOptions([
        'Skill Level' => 20
    ])
    ->setParams([
        'depth' => 12
    ]);

$lan = $stockfish->play($board->toFen());

$board->playLan('w', $lan);

echo $board->getMovetext();
```

```text
1.d4 Nf6 2.c4 c5 3.d5 e6 4.Nc3 exd5 5.cxd5 d6 6.e4 g6 7.Nf3 Bg7 8.h3
```

As you can see, Stockfish responds with 8.h3.

🎉 Can you beat the computer? Keep it up!
