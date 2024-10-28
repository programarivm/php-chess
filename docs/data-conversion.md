# Data Conversion

## FEN to Board

✨ FEN stands for Forsyth-Edwards Notation and is the standard way for describing chess positions using text strings.

At some point you'll definitely want to convert a FEN string into a chessboard object for further processing, and this can be done with the [Chess\FenToBoardFactory](https://github.com/chesslablab/php-chess/blob/main/tests/unit/FenToBoardFactoryTest.php) class according to the variants supported.

```php
use Chess\FenToBoardFactory;

$board = FenToBoardFactory::create('rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -');

$board->play('w', 'Nc3');
$board->play('b', 'Nc6');

echo $board->toFen();
```

```text
r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -
```

When a single parameter is passed in the form of a FEN string into the factory create method, it will be assumed that you want to create a classical chess board object. As discussed in the [Home](https://chesslablab.github.io/php-chess/) section, you may want to check out the self-explanatory tests for this class to learn more about it. The unit tests are the best documentation. They contain hundreds of real examples on how to use the PHP Chess library.

In this example the game history contains two moves only.

```php
var_dump($board->history);
```

```text
array(2) {
  [0]=>
  array(4) {
    ["castlingAbility"]=>
    string(4) "KQkq"
    ["sq"]=>
    string(2) "b1"
    ["move"]=>
    array(7) {
      ["pgn"]=>
      string(3) "Nc3"
      ["isCapture"]=>
      bool(false)
      ["isCheck"]=>
      bool(false)
      ["type"]=>
      string(48) "N[a-h]{0,1}[1-8]{0,1}[a-h]{1}[1-8]{1}[\+\#]{0,1}"
      ["color"]=>
      string(1) "w"
      ["id"]=>
      string(1) "N"
      ["sq"]=>
      array(2) {
        ["current"]=>
        string(0) ""
        ["next"]=>
        string(2) "c3"
      }
    }
    ["fen"]=>
    string(59) "rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -"
  }
  [1]=>
  array(4) {
    ["castlingAbility"]=>
    string(4) "KQkq"
    ["sq"]=>
    string(2) "b8"
    ["move"]=>
    array(7) {
      ["pgn"]=>
      string(3) "Nc6"
      ["isCapture"]=>
      bool(false)
      ["isCheck"]=>
      bool(false)
      ["type"]=>
      string(48) "N[a-h]{0,1}[1-8]{0,1}[a-h]{1}[1-8]{1}[\+\#]{0,1}"
      ["color"]=>
      string(1) "b"
      ["id"]=>
      string(1) "N"
      ["sq"]=>
      array(2) {
        ["current"]=>
        string(0) ""
        ["next"]=>
        string(2) "c6"
      }
    }
    ["fen"]=>
    string(60) "r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -"
  }
}
```

The initial FEN string is always accessible.

```php
echo $board->startFen;
```

```text
rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -
```

🎉 Well done! You just learned a well-known and frequently played chess opening.

## Board to PNG Image

✨ PNG stands for Portable Network Graphics and is a widely used format for image files. Not to be confused with PGN, the text-based file format to annotate chess games.

[Chess\Media\BoardToPng](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Media/BoardToPngTest.php) converts a chess board object to a PNG image.

```php
use Chess\FenToBoardFactory;
use Chess\Media\BoardToPng;

$board = FenToBoardFactory::create('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+');

$filename = (new BoardToPng($board, $flip = true))->output(__DIR__);
```

![Figure 1](https://raw.githubusercontent.com/chesslablab/php-chess/main/docs/data-conversion_01.png)

🎉 Try this thing! Share a puzzling chess position with friends for further study.

## Board to MP4

✨ Text-based PGN movetexts can be easily converted to MP4, a widely-used video format which comes in handy for pausing the games.

[Chess\Media\BoardToMp4](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Media/BoardToMp4Test.php) allows to convert a chess board object to an MP4 video.

```php
use Chess\Media\BoardToMp4;
use Chess\Variant\Classical\Board;

$movetext = '1.d4 Nf6 2.c4 c5 3.d5 e6 4.Nc3 exd5 5.cxd5 d6 6.e4 g6 7.Nf3 Bg7';

$board = new Board();

$filename = (new BoardToMp4($movetext, $board, $flip = false))->output(__DIR__);
```

🎉 MP4 videos are especially useful to pause the game at a specific position.

## Image to FEN

✨ A chess piece image recognizer has been created in the [chesslablab/perception](https://github.com/chesslablab/perception) repository with the help of a multilayer neural network.

[Chess\Media\ImgToPiecePlacement](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Media/ImgToPiecePlacementTest.php) relies on this recognizer to convert a GD image into a piece placement in FEN format.

```php
use Chess\Media\ImgToPiecePlacement;

$image = imagecreatefrompng(__DIR__ . '/01_kaufman.png');

$prediction = (new ImgToPiecePlacement($image))->predict();

echo $prediction;
```

```text
1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K
```
