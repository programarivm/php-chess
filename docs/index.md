# Features

PHP Chess is a library implemented in PHP that allows to create chess apps out-of-the-box.

One key feature is that it has been designed with OOP principles in mind and is thoroughly tested with plenty of unit tests. The unit tests are the best documentation. They contain hundreds of real examples on how to use the PHP Chess API.

Almost every class in the [src](https://github.com/chesslablab/php-chess/tree/master/src) folder represents a concept that is tested accordingly in the [tests/unit](https://github.com/chesslablab/php-chess/tree/master/tests/unit) folder, in other words, the structure of the [tests/unit](https://github.com/chesslablab/php-chess/tree/master/tests/unit) folder is mirroring the structure of the [src](https://github.com/chesslablab/php-chess/tree/master/src) folder. For further details on how to use a particular class, please feel free to browse the codebase and check out the corresponding tests.

The PHP Chess docs are more of a tutorial rather than an API description.

## Common Formats Supported

- Chess moves in LAN and PGN formats.
- Movetext processing in LAN, SAN and RAV formats.
- NAG support for SAN and RAV movetexts.
- UCI protocol.
- FEN conversion to chess board.
- Chess board conversion to PNG and JPG image.
- Chess board conversion to MP4 video.

| Acronym | Description |
| ------- | ---------- |
| LAN | Long Algebraic Notation |
| PGN | Portable Game Notation |
| SAN | Standard Algebraic Notation |
| RAV | Recursive Annotation Variation |
| NAG | Numeric Annotation Glyphs |
| UCI | Universal Chess Interface |
| FEN | Forsyth-Edwards Notation |

## Chess Variants

Three different variants are supported with the default one being classical chess.

| Variant | Chessboard |
| ------- | ---------- |
| Capablanca | [Chess\Variant\Capablanca\Board](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Variant/Capablanca/BoardTest.php) |
| Chess960 | [Chess\Variant\Chess960\Board](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Variant/Chess960/BoardTest.php) |
| Classical | [Chess\Variant\Classical\Board](https://github.com/chesslablab/php-chess/blob/master/tests/unit/Variant/Classical/BoardTest.php) |

## UCI Engines

Listed below are the UCI engines available at the moment.

| Name | UCI Engine |
| ---- | ---------- |
| Stockfish | [Chess\UciEngine\Stockfish](https://github.com/chesslablab/php-chess/blob/master/tests/unit/UciEngine/StockfishTest.php) |

## Object-Oriented API

Data processing with an object-oriented API. The chess board representation is an object of type [SplObjectStorage](https://www.php.net/manual/en/class.splobjectstorage.php) as opposed to a bitboard.

## Thoroughly Tested

PHP Chess has been developed with a test-driven development (TDD) approach. The [tests/unit](https://github.com/chesslablab/php-chess/tree/master/tests/unit) folder contains plenty of real examples.

## Lightweight

PHP dependencies required:

- Rubix ML for machine learning.
- Imagine for image processing.
