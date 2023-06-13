# Getting Started

The `Chess\Variant\Classical\Board` class is the easiest way to get started with PHP Chess.

```php
use Chess\Variant\Classical\Board;

$board = new Board();
```

Then, you're set up to play classical chess either in PGN or LAN format.

In PGN format:

```php
$board->play('w', 'e4');
```

In LAN format:

```php
$board->playLan('w', 'e2e4');
```

🎉 Congrats! 1.e4 is one of the best moves to start with.


PGN stands for Portable Game Notation and is a human-readable format that allows chess players to read and write chess games. Computers and graphic user interfaces (GUI) often prefer an easy-to-use, machine-readable format called Long Algebraic Notation (LAN) instead. So, for example, if you're integrating a JavaScript chessboard with a backend, you may want to make the chess moves in LAN format. On the other hand, PGN is more suitable for loading games annotated by humans.

Be that as it may, every time a move is made, the state of the board changes.

```php
var_dump($board->toFen());
```

```
string(55) "rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3"
```

This is the chess position in Forsyth–Edwards Notation (FEN) format after 1.e4.

📌 As soon as a `Chess\Variant\Classical\Board` object is instantiated we're already using terms such as FEN, LAN and PGN. Some familiarity with chess terms and concepts is required but if you're new to chess this tutorial will guide you through how to easily create amazing apps with PHP Chess.
