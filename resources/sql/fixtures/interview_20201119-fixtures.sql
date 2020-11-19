-- Fixtures for database "interview_20201119".

TRUNCATE game;
INSERT INTO game (id, name) VALUES (1, 'Game 1');
INSERT INTO game (id, name) VALUES (2, 'Game 2');

TRUNCATE "gamePlayer";
INSERT INTO "gamePlayer" (id, name) VALUES (1, 'Player 1');
INSERT INTO "gamePlayer" (id, name) VALUES (2, 'Player 2');
INSERT INTO "gamePlayer" (id, name) VALUES (3, 'Player 3');

TRUNCATE "gamePlayerSession";
INSERT INTO "gamePlayerSession" (id, "gameId", "gamePlayerId", version) VALUES (3, 2, 2, 'Version A');
INSERT INTO "gamePlayerSession" (id, "gameId", "gamePlayerId", version) VALUES (2, 1, 2, 'Version B');
INSERT INTO "gamePlayerSession" (id, "gameId", "gamePlayerId", version) VALUES (1, 1, 1, 'Version A');
INSERT INTO "gamePlayerSession" (id, "gameId", "gamePlayerId", version) VALUES (4, 2, 2, 'Version B');
INSERT INTO "gamePlayerSession" (id, "gameId", "gamePlayerId", version) VALUES (5, 2, 1, 'Version B');