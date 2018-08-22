### Command Line Interface (CLI)

Make sure to have an `.env` file in your app's root folder:

    APP_ENV=dev

    DB_DRIVER=mysql
    DB_HOST=localhost
    DB_USER=root
    DB_PASSWORD=password
    DB_NAME=pgn_chess
    DB_PORT=3306

#### `db-create.php`

Creates a MySQL PGN Chess database.

    php db-create.php
    This will remove the current PGN Chess database and the data will be lost.
    Do you want to proceed? (Y/N): y

#### `pgn-tomysql.php`

Converts a PGN file into a MySQL `INSERT` statement.

    php pgn-tomysql.php example.pgn > example.sql

This is the output generated.

    INSERT INTO games (Event, Site, Date, Round, White, Black, Result, WhiteTitle, BlackTitle, WhiteElo, BlackElo, WhiteUSCF, BlackUSCF, WhiteNA, BlackNA, WhiteType, BlackType, EventDate, EventSponsor, Section, Stage, Board, Opening, Variation, SubVariation, ECO, NIC, Time, UTCTime, UTCDate, TimeControl, SetUp, FEN, Termination, Annotator, Mode, PlyCount, movetext) VALUES ('TCh-FRA Top 12 2018', 'Brest FRA', '2018.05.28', '3.3', 'Dornbusch, Tatiana', 'Feller, Sebastien', '0-1', null, null, '2290', '2574', null, null, null, null, null, null, '2018.05.26', null, null, null, null, null, null, null, 'E11', null, null, null, null, null, null, null, null, null, null, null, '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5 8.d5 e59.O-O Nbd7 10.b4 a5 11.b5 g6 12.Nb1 Nh5 13.Bh6 Ng7 14.Qc2 Nb6 15.Nbd2 Bd716.Kh1 Qc7 17.Rae1 Rae8 18.Ng1 Bc8 19.f4 exf4 20.Qc3 f6 21.Rxf4 Nd7 22.Bc2Ne5 23.h3 Nf7 24.Bxg7 Kxg7 25.Rf2 Kg8 26.Ngf3 Ne5 27.Ref1 Bd8 28.Kg1 Qg729.Nb3 b6 30.Nbd2 Qh6 31.Nxe5 fxe5 32.Rxf8+ Rxf8 33.Rxf8+ Kxf8 34.a4 Kg735.Bd1 Qh4 36.Kf1 Qf4+ 37.Bf3 h5 38.Ke2 Kh6 39.Qe3 g5 40.Qxf4 exf4 41.e5dxe5 42.d6 Be6 43.Bc6 g4 44.hxg4 hxg4 45.Kd3 Kg5 46.Ke4 Kf6 47.Be8 Bg8 48.Bd7 Be6 49.Bc6 Bf5+ 50.Kd5 f3 51.g3 f2 52.Be8 e4 53.Nf1 Be6+ 54.Kc6 Ke555.Bh5 Kd4 0-1'),('11. KIIT Elite Open 2018', 'Bhubaneswar IND', '2018.05.28', '5.3', 'Nitin, S', 'Amonatov, Farrukh', '0-1', null, null, '2432', '2608', null, null, null, null, null, null, '2018.05.25', null, null, null, null, null, null, null, 'B90', null, null, null, null, null, null, null, null, null, null, null, '1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 a6 6.Be3 e5 7.Nb3 Be7 8.h3 b59.a4 b4 10.Nd5 Nbd7 11.Nxe7 Kxe7 12.Qd2 a5 13.O-O-O Qc7 14.f3 Bb7 15.g4Rhc8 16.Bb5 Bc6 17.Bxc6 Qxc6 18.Bg5 Ra6 19.Qe2 Nb6 20.Bxf6+ gxf6 21.f4 Qc422.Rd3 Nxa4 23.g5 fxg5 24.fxe5 dxe5 25.Rhd1 Rac6 26.R1d2 Nc5 27.Nxc5 Qxc528.Qg4 Kf8 29.Rf3 Kg8 30.Qf5 Rf8 31.Rg3 Rg6 32.Rd5 Qc4 33.Rxg5 b3 34.Rd2bxc2 35.Rxc2 Qd4 36.Rcg2 Rd8 37.Rxg6+ hxg6 38.Rxg6+ Kf8 39.Qf3 Qa4 40.Rg2Qa1+ 41.Kc2 Rc8+ 42.Kd3 Qc1 43.Qf6 Qc4+ 44.Ke3 Qd4+ 45.Kf3 Qd3+ 46.Kg4Qxe4+ 47.Kg3 Qg6+ 48.Qxg6 fxg6 49.Kf3 Kf7 50.Ke4 Kf6 51.Rf2+ Ke6 52.Rg2Rc4+ 53.Ke3 Kf5 54.Rf2+ Rf4 55.Rc2 Rh4 56.Rf2+ Ke6 57.Rf3 Rb4 0-1');

#### `pgn-syntax.php`

Checks the syntax of a PGN file.

    php pgnsyntax.php games.pgn
    This will search for syntax errors in the PGN file.
    Large files (for example 50MB) may take a few seconds to be parsed.
    Do you want to proceed? (Y/N): y
    Whoops! Sorry but this is not a valid PGN file.
    --------------------------------------------------------
    Site: Bhubaneswar IND
    Date: 2018.05.28
    Round: 5.3
    White: Nitin, S
    Black: Amonatov, Farrukh
    Result: 0-1
    WhiteElo: 2432
    BlackElo: 2608
    EventDate: 2018.05.25
    ECO: B90
    --------------------------------------------------------
    Event: 11. KIIT Elite Open 2018
    Site: Bhubaneswar IND
    Date: 2018.05.28
    Round: 5.17
    White: Raahul, V S
    Black: Neverov, Valeriy
    Result: 1/2-1/2
    WhiteElo: 2231
    BlackElo: 2496
    EventDate: 2018.05.25
    ECO: A25
    1.foo f5 2.Nc3 Nf6 3.g3 e5 4.Bg2 Nc6 5.e3 Bb4 6.Nge2 O-O 7.O-O d6 8.Nd5 Nxd5 9.cxd5 Ne7 10.d4 Ba5 11.b4 Bb6 12.dxe5 dxe5 13.Qb3 Kh8 14.a4 a6 15. Bb2 Ng6 16.a5 Ba7 17.Qc3 Re8 18.Nf4 Re7 19.Nxg6+ hxg6 20.Rac1 Rb8 21.b5 b6 22.Ba3 Rf7 23.axb6 Rxb6 24.Bc5 e4 25.Bxb6 Bxb6 26.bxa6 Bxa6 27.Rfd1 Rd7 28.Qe5 Rd6 29.Bf1 Bxf1 30.Kxf1 c6 31.Kg2 Kh7 32.h4 cxd5 33.h5 Qd7 34.Rh1 g5 35.Rc8 f4 36.h6 f3+ 37.Kg1 Rxh6 38.Rh8+ Kg6 39.R1xh6+ gxh6 40.Rg8+ Kh5 41.Qf6 Bxe3 42.fxe3 Qc7 43.Qg6+ Kg4 44.Qe6+ Kxg3 45.Rc8 Qa7 46.Qd6+ Kg4 47.Qe6+ Kg3 48.Qd6+ Kg4 1/2-1/2
    --------------------------------------------------------
    Event: TCh-FRA Top 12 2018
    Site: Brest FRA
    Date: 2018.05.28
    Round: 3.6
    White: Eljanov, Pavel
    Black: Ragger, Markus
    Result: 1/2-1/2
    WhiteElo: 2702
    BlackElo: 2672
    EventDate: 2018.05.26
    ECO: A34
    1.Nf3 Nf6 20.c4 c5 3.Nc3 d5 4.cxd5 Nxd5 5.e3 e6 6.Bb5+ Bd7 7.Be2 Nc6 8.O-O Be7 9.d4 cxd4 10.Nxd5 exd5 11.exd4 O-O 12.Ne5 Bf5 13.Be3 Bf6 14.Nxc6 bxc6 15.Bd3 Bxd3 16.Qxd3 Qb6 17.Rfc1 Rfe8 18.Qc3 1/2-1/2
    --------------------------------------------------------
    Please check these games. Do they provide the STR (Seven Tag Roster)? Is the movetext valid?

#### `db-seed.php`

Seeds the PGN Chess database with games.

    php db-seed.php Alekhine.pgn
    This will search for valid PGN games in the file.
    Large files (for example 50MB) may take a few seconds to be inserted into the database.
    Do you want to proceed? (Y/N): y
    Good! This is a valid PGN file. 3201 games were inserted into the database.
