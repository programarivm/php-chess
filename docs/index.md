# PHP Chess

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/license/mit/)
[![Contributors](https://img.shields.io/github/contributors/chesslablab/php-chess)](https://github.com/chesslablab/php-chess/graphs/contributors)
[![Downloads](https://img.shields.io/packagist/dt/chesslablab/php-chess.svg)](https://packagist.org/packages/chesslablab/php-chess)
[![Latest Stable Version](https://poser.pugx.org/chesslablab/php-chess/v/stable)](https://packagist.org/packages/chesslablab/php-chess)
![GitHub Workflow Status](https://github.com/chesslablab/php-chess/actions/workflows/php.yml/badge.svg)

PHP Chess is a library implemented in PHP that allows to create chess apps out-of-the-box.

One key feature is that it has been designed with OOP principles in mind and is thoroughly tested with plenty of unit tests. The unit tests are the best documentation. They contain hundreds of real examples on how to use PHP Chess.

Almost every class in the [src](https://github.com/chesslablab/php-chess/tree/main/src) folder represents a concept that is tested accordingly in the [tests/unit](https://github.com/chesslablab/php-chess/tree/main/tests/unit) folder, in other words, the structure of the [tests/unit](https://github.com/chesslablab/php-chess/tree/main/tests/unit) folder is mirroring the structure of the [src](https://github.com/chesslablab/php-chess/tree/main/src) folder. For further details on how to use a particular class, please feel free to browse the codebase and check out the corresponding tests.

The PHP Chess docs are more of a tutorial rather than an API description.

## Common Formats Supported

- Chess moves in LAN and PGN formats.
- Movetext processing in LAN, SAN and RAV formats.
- NAG support for SAN and RAV movetexts.
- UCI protocol.
- FEN conversion to chess board.
- Chess board conversion to PNG and JPG image.
- Chess board conversion to MP4 video.

| Acronym | Description                    |
| :------ | :----------------------------- |
| LAN     | Long Algebraic Notation        |
| PGN     | Portable Game Notation         |
| SAN     | Standard Algebraic Notation    |
| RAV     | Recursive Annotation Variation |
| NAG     | Numeric Annotation Glyphs      |
| UCI     | Universal Chess Interface      |
| FEN     | Forsyth-Edwards Notation       |

## Chess Variants

Multiple variants are supported with the default one being classical chess.

| Variant | Chessboard |
| ------- | ---------- |
| Capablanca | [Chess\Variant\Capablanca\Board](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Variant/Capablanca/BoardTest.php) |
| Capablanca-Fischer | [Chess\Variant\CapablancaFischer\Board](https://github.com/chesslablab/php-chess/blob/main/src/Variant/CapablancaFischer/Board.php) |
| Chess960 | [Chess\Variant\Chess960\Board](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Variant/Chess960/BoardTest.php) |
| Classical | [Chess\Variant\Classical\Board](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Variant/Classical/BoardTest.php) |
| Dunsany | [Chess\Variant\Dunsany\Board](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Variant/Dunsany/BoardTest.php) |
| Losing Chess | [Chess\Variant\Losing\Board](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Variant/Losing/BoardTest.php) |
| RacingKings | [Chess\Variant\RacingKings\Board](https://github.com/chesslablab/php-chess/blob/main/tests/unit/Variant/RacingKings/BoardTest.php) |

## UCI Engines

Listed below are the UCI engines available at the moment.

- Stockfish

## Object-Oriented

The chess board representation is an object of type [SplObjectStorage](https://www.php.net/manual/en/class.splobjectstorage.php) as opposed to a bitboard.

## Thoroughly Tested

PHP Chess has been developed with a test-driven development (TDD) approach. The [tests/unit](https://github.com/chesslablab/php-chess/tree/main/tests/unit) folder contains plenty of real examples.

## Lightweight

PHP dependencies required:

- Rubix ML for machine learning.
- Imagine for image processing.

## Installation

### Requirements

- PHP >= 8.1
- Stockfish >= 15.1

### Composer installation

```text
composer require chesslablab/php-chess
```
