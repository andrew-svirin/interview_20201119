-- Schema of database "interview_20201119".

CREATE TABLE game
(
    id   integer not null
        CONSTRAINT game_pk
            PRIMARY KEY,
    name text
);

CREATE TABLE "gamePlayer"
(
    id   serial not null
        CONSTRAINT gameplayer_pk
            PRIMARY KEY,
    name text
);

CREATE TABLE "gamePlayerSession"
(
    id             serial NOT NULL
        CONSTRAINT gameplayersession_pk
            PRIMARY KEY,
    "gameId"       integer,
    "gamePlayerId" integer,
    version        text,
    CONSTRAINT game_fk
      FOREIGN KEY("gameId")
	  REFERENCES game(id),
	CONSTRAINT gameplayer_fk
      FOREIGN KEY("gamePlayerId")
	  REFERENCES "gamePlayer"(id)
);