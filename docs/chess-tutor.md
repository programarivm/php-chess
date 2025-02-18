# Chess Tutor

## Explain a FEN Position

✨ Chess beginners often think they can checkmate the opponent's king quickly. However, there are so many different things to consider in order to understand a position.

[Chess\Tutor\FenEvaluation](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Tutor/FenEvaluationTest.php) helps you improve your chess thinking process by evaluating a FEN position in terms of chess concepts like the example below.

```php
use Chess\Eval\CompleteFunction;
use Chess\Tutor\FenEvaluation;
use Chess\Variant\Classical\FenToBoardFactory;

$f = new CompleteFunction();

$board = FenToBoardFactory::create('8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1');

$paragraph = (new FenEvaluation($f, $board))->paragraph;

$text = implode(' ', $paragraph);

echo $text;
```

```text
White has a decisive material advantage. White has a slightly better control of the center. The white player is pressuring more squares than its opponent. White has an absolute pin advantage. White has the bishop pair. Black's king has more safe squares to move to than its counterpart. Black's king can be checked so it is vulnerable to forced moves. The knight on e6 is pinned shielding the king so it cannot move out of the line of attack because the king would be put in check. Overall, 5 evaluation features are favoring White.
```

🎉 This is a form of abductive reasoning.

## Explain a PGN Move

✨ Typically, chess engines won't provide an explanation in easy-to-understand language about how a move changes the position on the board.

[Chess\Tutor\PgnEvaluation](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Tutor/PgnEvaluationTest.php) explains how a particular move changes the position.

```php
use Chess\Eval\CompleteFunction;
use Chess\Play\SanPlay;
use Chess\Tutor\PgnEvaluation;

$pgn = 'd4';

$f = new CompleteFunction();

$movetext = '1.Nf3 d5 2.g3 c5';
$board = (new SanPlay($movetext))->validate()->board;

$paragraph = (new PgnEvaluation($pgn, $f, $board))->paragraph;

$text = implode(' ', $paragraph);

echo $text;
```

```text
Black has a slight space advantage. White has a slight protection advantage. White has a slight attack advantage. White's king can be checked so it is vulnerable to forced moves. These pieces are hanging: The rook on a1, the rook on h1, the rook on a8, the rook on h8, the pawn on c5. The pawn on c5 is unprotected. The pawn on c5 is under threat of being attacked. Overall, 0 evaluation features are favoring either player.
```

The resulting text may sound a little robotic but it can be easily rephrased by the AI of your choice to make it sound more human-like.

## Explain a Good PGN Move

✨ It's often difficult for beginners to understand why a move is good.

With the help of an UCI engine [Chess\Tutor\GoodPgnEvaluation](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Tutor/GoodPgnEvaluationTest.php) can explain the why of a good move.

```php
use Chess\Eval\CompleteFunction;
use Chess\Play\SanPlay;
use Chess\Tutor\GoodPgnEvaluation;
use Chess\UciEngine\UciEngine;
use Chess\UciEngine\Details\Limit;

$limit = new Limit();
$limit->depth = 12;

$stockfish = new UciEngine('/usr/games/stockfish');

$f = new CompleteFunction();

$movetext = '1.d4 d5 2.c4 Nc6 3.cxd5 Qxd5 4.e3 e5 5.Nc3 Bb4 6.Bd2 Bxc3 7.Bxc3 exd4 8.Ne2';
$board = (new SanPlay($movetext))->validate()->board;

$goodPgnEvaluation = new GoodPgnEvaluation($limit, $stockfish, $f, $board);

$pgn = $goodPgnEvaluation->pgn;
$paragraph = implode(' ', $goodPgnEvaluation->paragraph);

echo $pgn . PHP_EOL;
echo $paragraph . PHP_EOL;
```

```text
Bg4
The black player is pressuring more squares than its opponent. The black pieces are timidly approaching the other side's king. Black has a relative pin advantage. These pieces are hanging: Black's queen on d5, the rook on a8, the rook on h8, the pawn on b7, the pawn on c7, the pawn on g7, the bishop on g4, the rook on h1. The knight on e2 is pinned shielding a piece that is more valuable than the attacking piece. Overall, 7 evaluation features are favoring Black.
```

🎉 Let's do this!
