## PGN Chess

[![Build Status](https://travis-ci.org/programarivm/pgn-chess.svg?branch=master)](https://travis-ci.org/programarivm/pgn-chess)
[![Documentation Status](https://readthedocs.org/projects/pgn-chess/badge/?version=latest)](https://pgn-chess.readthedocs.io/en/latest/?badge=latest)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

<p align="center">
	<img src="https://github.com/programarivm/pgn-chess/blob/master/resources/chess-move.jpg" />
</p>

This is a PGN (Portable Game Notation) tool for running chess games (player vs player) in PHP applications. It is a chess board representation to play and validate PGN games.

### Install

Via composer:

    $ composer require programarivm/pgn-chess

### Instantiation

Just instantiate a game and play PGN moves:

```php
<?php

use PGNChess\Game;

$game = new Game;

$isLegalMove = $game->play('w', 'e4');
```
All action takes place in the `$game` object. The call to the `$game->play` method returns `true` or `false` depending on whether or not a chess move can be run on the board.

### Documentation

For further information please read the [Documentation](https://pgn-chess.readthedocs.io/en/latest/).

### License

The GNU General Public License.

### Contributions

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "PGN Chess Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)
- Say hello on [Google+](https://plus.google.com/+Programarivm)

Many thanks.
