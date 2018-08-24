CREATE DATABASE IF NOT EXISTS `pgn_chess_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `pgn_chess_test`;

CREATE USER 'test'@'localhost' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP ON pgn_chess_test.* TO 'test'@'localhost';

CREATE TABLE games (
    Event VARCHAR(64) NULL, # STR (Seven Tag Roster)
    Site VARCHAR(64) NULL,
    Date VARCHAR(64) NULL,
    Round VARCHAR(8) NULL,
    White VARCHAR(64) NULL,
    Black VARCHAR(64) NULL,
    Result VARCHAR(8) NULL,
    WhiteTitle VARCHAR(16) NULL, # player related information
    BlackTitle VARCHAR(16) NULL,
    WhiteElo VARCHAR(8) NULL,
    BlackElo VARCHAR(8) NULL,
    WhiteUSCF VARCHAR(8) NULL,
    BlackUSCF VARCHAR(8) NULL,
    WhiteNA VARCHAR(8) NULL,
    BlackNA VARCHAR(8) NULL,
    WhiteType VARCHAR(8) NULL,
    BlackType VARCHAR(8) NULL,
    EventDate VARCHAR(32) NULL, # event related information
    EventSponsor VARCHAR(64) NULL,
    Section VARCHAR(32) NULL,
    Stage VARCHAR(32) NULL,
    Board VARCHAR(32) NULL,
    Opening VARCHAR(32) NULL, # opening information
    Variation VARCHAR(32) NULL,
    SubVariation VARCHAR(32) NULL,
    ECO VARCHAR(32) NULL,
    NIC VARCHAR(32) NULL,
    Time VARCHAR(32) NULL, #time and date related information
    UTCTime VARCHAR(32) NULL,
    UTCDate VARCHAR(32) NULL,
    TimeControl VARCHAR(32) NULL, # time control
    SetUp VARCHAR(32) NULL, # alternative starting positions
    FEN VARCHAR(32) NULL,
    Termination VARCHAR(32) NULL, # game conclusion
    Annotator VARCHAR(32) NULL, # miscellaneous
    Mode VARCHAR(32) NULL,
    PlyCount VARCHAR(32) NULL,
    movetext  TEXT NOT NULL
);
