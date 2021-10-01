## PHP Chess

[![Latest Stable Version](https://poser.pugx.org/chesslablab/php-chess/v/stable)](https://packagist.org/packages/chesslablab/php-chess)
[![Build Status](https://app.travis-ci.com/chesslablab/php-chess.svg?branch=master)](https://app.travis-ci.com/github/chesslablab/php-chess)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

A chess library for PHP.

Currently used on [Redux Chess](https://github.com/chesslablab/redux-chess), which is a React chessboard connected to a [PHP Chess server](https://github.com/chesslablab/chess-server). Check out [this demo](https://programarivm.github.io/demo-redux-chess).

> Please note the sandbox server might not be up and running all the time.

### Install

Via composer:

    $ composer require chesslablab/php-chess

### Play Chess

```php
use Chess\Game;

$game = new Game();

$game->play('w', 'e4');
$game->play('b', 'e5');
```
The call to the `$game->play` method returns `true` or `false` depending on whether or not a chess move can be run on the board.

### Play Chess With an AI

Pass the `Game::MODE_AI` parameter when instantiating a `$game`:

```php
$game = new Game(Game::MODE_AI);

$game->play('w', 'e4');
$game->play('b', $game->response());
$game->play('w', 'e5');
$game->play('b', $game->response());
```

The AIs are stored in the [`model`](https://github.com/chesslablab/php-chess/tree/master/model) folder. The default is `a1.model`, if you want to play with a different AI pass it as a second parameter to the `Chess\Game` constructor:

```php
$game = new Game(Game::MODE_AI, 'a2.model');

$game->play('w', 'e4');
$game->play('b', $game->response());
$game->play('w', 'e5');
$game->play('b', $game->response());
```

---

For further information you're invited to read my learning journey:

- [Demystifying AI Through a Human-Like Chess Engine](https://medium.com/geekculture/demystifying-ai-through-a-human-like-chess-engine-5f71e3896cc9)
- [Two Things That My AI Project Required](https://medium.com/geekculture/two-things-that-my-ai-project-required-50000297053b)
- [What Are Some Healthy Tips to Reduce Cognitive Load?](https://medium.com/geekculture/what-are-some-healthy-tips-to-reduce-cognitive-load-4f91b695a3cb)
- [How to Take Normalized Heuristic Pictures](https://medium.com/geekculture/how-to-take-normalized-heuristic-pictures-79ca0df4cdec)
- [Equilibrium, Yin-Yang Chess](https://medium.com/geekculture/equilibrium-yin-yang-chess-292e044be46b)
- [Adding Classes to a SOLID Codebase Without Breaking Anything Else](https://medium.com/geekculture/adding-classes-to-a-solid-codebase-without-breaking-anything-else-99e6c5a5f3e4)
- [Preparing a Dataset for Machine Learning With PHP](https://ai.plainenglish.io/preparing-a-dataset-for-machine-learning-with-php-fd68dd85187e)
- [Converting a FEN Chess Position Into a PGN Move](https://medium.com/geekculture/converting-a-fen-chess-position-into-a-pgn-move-4a278d81b21f)
- [A React Chessboard with Redux and Hooks in Few Lines](https://medium.com/geekculture/a-react-chessboard-with-redux-and-hooks-in-few-lines-6009cb724bb)
- [How to Test a Local React NPM Package With Ease](https://javascript.plainenglish.io/testing-a-local-react-npm-package-with-ease-7d0668676ddb)
- [TDDing a React App With Jest the Easy Way](https://medium.com/geekculture/tdding-a-react-app-with-jest-the-easy-way-8ddb64aeaba6)
- [How to Test React Components With Joy](https://javascript.plainenglish.io/looking-forward-to-testing-react-components-with-joy-5bb3f86c21d7)
- [My First Integration Test in a Redux Hooked App](https://javascript.plainenglish.io/my-first-integration-test-in-a-redux-hooked-app-3b189addd46e)
- [Creating a Local WebSocket Server With TLS/SSL Is Easy as Pie](https://medium.com/geekculture/creating-a-local-websocket-server-with-tls-ssl-is-easy-as-pie-de1a2ef058e0)
- [A Simple Example of SSL/TLS WebSocket With ReactPHP and Ratchet](https://medium.com/geekculture/a-simple-example-of-ssl-tls-websocket-with-reactphp-and-ratchet-e03be973f521)
- [Newbie Tutorial on How to Rate-Limit a WebSocket Server](https://medium.com/geekculture/newbie-tutorial-on-how-to-rate-limit-a-websocket-server-8e28642ad5ff)
