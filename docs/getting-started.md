# Getting Started

The `Chess\Game` class is probably the quickest way to get started with PHP Chess.

```php
use Chess\Game;

$game = new Game(
    Game::VARIANT_CLASSICAL,
    Game::MODE_FEN
);
```

Two parameters are required to instantiate a game: A chess variant and a game mode. The most common way to start is with `Game::VARIANT_CLASSICAL` and `Game::MODE_FEN`. Then, you're set up to play chess in either PGN or in LAN format.

PGN format:

```php
$game->play('w', 'e4');
```

LAN format:

```php
$game->playLan('w', 'e2e4');
```

🎉 Congrats! 1.e4 is one of the best moves to start with.


PGN stands for Portable Game Notation and is a human-readable format that allows chess players to read and write chess games. Computers and graphic user interfaces (GUI) often prefer an easy-to-use, machine-readable format called Long Algebraic Notation (LAN) instead. So, for example, if you're integrating a JavaScript chessboard with a backend, you may want to make the chess moves in LAN format. On the other hand, PGN is more suitable for loading games annotated by humans.

Be that as it may, every time a move is made, the state of the game changes.

```php
var_dump($game->state());
```

```
object(stdClass)#6486 (10) {
  ["turn"]=>
  string(1) "b"
  ["pgn"]=>
  string(2) "e4"
  ["castlingAbility"]=>
  string(4) "KQkq"
  ["movetext"]=>
  string(4) "1.e4"
  ["fen"]=>
  string(55) "rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3"
  ["isCheck"]=>
  bool(false)
  ["isMate"]=>
  bool(false)
  ["isStalemate"]=>
  bool(false)
  ["isFivefoldRepetition"]=>
  bool(false)
  ["mode"]=>
  string(3) "fen"
}
```

📌 As soon as we instantiate our first `Chess\Game` object we're already using chess terms such as FEN, LAN and PGN. Some familiarity with chess terms and concepts is required to use the PHP Chess library, however, if you're new to chess this tutorial will guide you through how to easily create amazing chess web apps.
