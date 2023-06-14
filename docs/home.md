# Home

PHP Chess is a library implemented in PHP that allows to create chess apps out-of-the-box.

One key feature is that it has been designed with OOP principles in mind and is thoroughly tested with plenty of unit tests. The unit tests are the best documentation. They contain hundreds of real examples on how to use the PHP Chess API.

Almost every class in the `src` folder represents a concept that is tested accordingly in the `tests/unit` folder, in other words, the structure of the `tests/unit` folder is mirroring the structure of the `src` folder. For further details on how to use a particular class, please feel free to browse the codebase and check out the corresponding tests.

The PHP Chess docs are more of a tutorial rather than an API description.

## Features

### Object-Oriented API

Data processing with an object-oriented API. The chess board representation is an object of type `SplObjectStorage` as opposed to a bitboard.

### Easy to Learn

Almost everything in PHP Chess can be done with a chessboard object. There are three different variants supported with the default one being classical chess.

| Variant | Chessboard |
| ------- | ---------- |
| Capablanca | `Chess\Variant\Capablanca\Board` |
| Chess960 | `Chess\Variant\Chess960\Board` |
| Classical | `Chess\Variant\Classical\Board` |

### Lightweight

Requires two PHP dependencies: Rubix ML for machine learning and Imagine for image processing.

### Thoroughly Tested

PHP Chess has been developed with a test-driven development (TDD) approach. The structure of the `tests/unit` folder mirrors the structure of the `src` folder and contains plenty of real examples.
