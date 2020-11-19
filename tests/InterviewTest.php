<?php

namespace AndrewSvirin\Interview\Tests;

use AndrewSvirin\Examples\BarClass;
use AndrewSvirin\Examples\FooAbstract;
use AndrewSvirin\Interview\Adapters\HTMLAdapter;
use AndrewSvirin\Interview\Adapters\PostgresAdapter;
use AndrewSvirin\Interview\Builders\OutputBuilder;
use AndrewSvirin\Interview\Factories\DBAdapterFactory;
use AndrewSvirin\Interview\Services\APIClient;
use AndrewSvirin\Interview\Requests\Feedback\CreateFeedbackRequest;

class InterviewTest extends BaseTestCase
{

    /**
     * Create connection and check it.
     * Task 1. Write some code that connects to a postgresql database using the username: john,
     * password: pw123 on localhost with the database called "intelligence"
     * @group connection
     */
    public function testConnection()
    {
        // Or establish connection with custom config by passing arguments .
        // Example:
        // $client = $this->client([
        //        'driver' => 'pgsql',
        //        'host' => 'localhost',
        //        'name' => 'intelligence',
        //        'username' => 'john',
        //        'password' => 'pw123',
        //        'port' => 5432,
        // ]);
        $client = $this->client();
        $client->connect();
        $this->assertTrue($client->isConnected());
        $client->close();
    }

    /**
     * Create query.
     * Task 2. Write some code that queries the database, using a single Postgresql query, that tells us
     * how many game players we have for each version of all games in the database. The data
     * should give us the id, name and version of each game, as well as the number of players.
     * @group query
     */
    public function testQuery()
    {
        $client = $this->client();
        $client->connect();
        $result = $client->query('
            SELECT 
                "gameId", 
                version, 
                game_version_count, 
                name 
            FROM (
                SELECT
                    "gameId",
                    version, COUNT(*) as game_version_count
                FROM "gamePlayerSession"
                GROUP BY version, "gameId"
            ) sessions
            INNER JOIN game ON game.id = sessions."gameId"
            ORDER BY game.id, game_version_count
        ');
        $client->close();

        $this->assertEquals([
            [
                'gameId' => '1',
                'version' => 'Version B',
                'game_version_count' => '1',
                'name' => 'Game 1',
            ],
            [
                'gameId' => '1',
                'version' => 'Version A',
                'game_version_count' => '1',
                'name' => 'Game 1',
            ],
            [
                'gameId' => '2',
                'version' => 'Version A',
                'game_version_count' => '1',
                'name' => 'Game 2',
            ],
            [
                'gameId' => '2',
                'version' => 'Version B',
                'game_version_count' => '2',
                'name' => 'Game 2',
            ],
        ], $result);
    }

    /**
     * Output analysis data.
     * Display Players those had sessions yet vs had not sessions in html table format.
     * Display Most popular Game in html panel format.
     * Task 3. Write some code that displays the information in any way that you wish,
     * focusing on displaying the information clearly and in a way that is useful for analysis.
     * @group output
     */
    public function testOutput()
    {
        $htmlAdapter = new HTMLAdapter();
        $output = new OutputBuilder();
        $result = $output
            ->addElement($htmlAdapter->table(
                [
                    'Players had sessions',
                    'Players had not sessions yet',
                ],
                [
                    [5, 1],
                ],
                'layers those had sessions yet vs had not sessions'
            ))
            ->addElement($htmlAdapter->panel(
                'Game',
                'Game 1',
                'Most popular Game'
            ))
            ->output();

        $this->assertEquals(
            '<table><caption>layers those had sessions yet vs had not sessions</caption><thead>' .
            '<tr><th>Players had sessions</th><th>Players had not sessions yet</th></tr></thead><tbody>' .
            '<tr><td>5</td><td>1</td></tr></tbody></table><panel><h2>Most popular Game</h2><div><h3>Game</h3>' .
            ': <b>Game 1</b></div></panel>', $result);
    }

    /**
     * Create `feedback` table.
     * Task 4. Let's imagine we wanted to include a database table where we would store feedback
     * submitted by players, for a game that they are playing. The feedback would be submitted by
     * them by inputting a string of text into a single text input which is then sent to our back-end
     * for processing. Please explain how that table would look.
     * @group create-table
     */
    public function testCreateTable()
    {
        $client = $this->client();
        $client->connect();

        $client->query('DROP TABLE IF EXISTS feedback');

        $client->query('
            CREATE TABLE "feedback" (
                id serial NOT NULL
                    CONSTRAINT feedback_pk
                        PRIMARY KEY,
                "gamePlayerSessionId" integer,
                value text
            );
        ');

        $result = $client->query('
            SELECT
                table_name,
                column_name,
                data_type
            FROM
                information_schema.columns
            WHERE
                table_name = $1
        ', ['feedback']);

        $client->query('DROP TABLE feedback');
        $client->close();

        $this->assertEquals([
            [
                'table_name' => 'feedback',
                'column_name' => 'id',
                'data_type' => 'integer',
            ],
            [
                'table_name' => 'feedback',
                'column_name' => 'gamePlayerSessionId',
                'data_type' => 'integer',
            ],
            [
                'table_name' => 'feedback',
                'column_name' => 'value',
                'data_type' => 'text',
            ],
        ], $result);
    }

    /**
     * Add indexes.
     * Task 5. There are roughly 100 games in the game table, 100000 players in the gamePlayer, over 10
     * million sessions in the gamePlayerSession table and roughly 1 million entries in your
     * feedback table. Let's say we are going to be running the above two queries frequently. How
     * would you suggest to optimize the database for that to happen?
     */
    public function testIndexes()
    {
        // I hope we talking about select query and sub query from Task 2.
        // Better to add indexes for fields by those are applying conditions.

        $client = $this->client();
        $client->connect();

        // Add foreign key constraints for table `gamePlayerSession`.
        // Foreign key will also add index key.
        $client->query('
            ALTER TABLE "gamePlayerSession" 
            ADD CONSTRAINT game_fk
                FOREIGN KEY("gameId")
	                REFERENCES game(id)
	    ');
        $client->query('
            ALTER TABLE "gamePlayerSession" 
            ADD CONSTRAINT gameplayer_fk
                FOREIGN KEY("gamePlayerId")
	                REFERENCES "gamePlayer"(id)
	    ');

        // Create feedback table from Task 4.
        $client->query('
            CREATE TABLE "feedback" (
                id serial NOT NULL
                    CONSTRAINT feedback_pk
                        PRIMARY KEY,
                "gamePlayerSessionId" integer,
                value text
            );
        ');

        // Add foreign key constraints for table `gamePlayerSession`.
        // Foreign key will also add index key.
        $client->query('
            ALTER TABLE "feedback" 
            ADD CONSTRAINT gameplayersession_fk
                FOREIGN KEY ("gamePlayerSessionId")
                    REFERENCES "gamePlayerSession" (id)
	    ');

        // Add complex index for `version` and `gameId` because by these columns goes group by.
        $client->query('CREATE INDEX version_gameid_idx ON "gamePlayerSession"(version, "gameId")');

        $result = $client->query('
            SELECT 
                "gameId", 
                version, 
                game_version_count, 
                name 
            FROM (
                SELECT
                    "gameId",
                    version, COUNT(*) as game_version_count
                FROM "gamePlayerSession"
                GROUP BY version, "gameId"
            ) sessions
            INNER JOIN game ON game.id = sessions."gameId"
            ORDER BY game.id, game_version_count
        ');

        $this->assertEquals([
            [
                'gameId' => '1',
                'version' => 'Version B',
                'game_version_count' => '1',
                'name' => 'Game 1',
            ],
            [
                'gameId' => '1',
                'version' => 'Version A',
                'game_version_count' => '1',
                'name' => 'Game 1',
            ],
            [
                'gameId' => '2',
                'version' => 'Version A',
                'game_version_count' => '1',
                'name' => 'Game 2',
            ],
            [
                'gameId' => '2',
                'version' => 'Version B',
                'game_version_count' => '2',
                'name' => 'Game 2',
            ],
        ], $result);

        $client->query('
            ALTER TABLE "gamePlayerSession" 
            DROP CONSTRAINT game_fk
	    ');
        $client->query('
            ALTER TABLE "gamePlayerSession" 
            DROP CONSTRAINT gameplayer_fk
	    ');
        $client->query('DROP INDEX version_gameid_idx');
        $client->query('DROP TABLE feedback');
        $client->close();
    }

    /**
     * Table denormalization.
     * Task 6. Let’s imagine that to the gamePlayerSession table, we add both a “gameName” and
     * “gamePlayerName” field, to store the name of the game and name of the player
     * respectively, within the gamePlayerSession table. Please explain whether this is a good or
     * bad idea, and why.
     */
    private function testDenormalization()
    {
        // When we use RDB then it is not recommended to store denormalized data for main tables.
        // But for cache purpose, to avoid joins with another tables, we can duplicate columns
        // of `gameName` or `gamePlayerName`.
        // But in denormalized storage it is complex to update values for such columns, we should track such columns
        // for process they in transaction.
    }

    /**
     * Simulate static cases.
     * Task 7. Write some code, with a focus on OOP, that shows an understanding of the concept of
     * static. This code should show at least 4 common/valid use-cases of the static keyword.
     * @group static
     */
    public function testStatic()
    {
        $config = $this->config['database'];
        $dbAdapter = DBAdapterFactory::produce($config['driver']);

        // Case 1. Simple invoke static method without instance of class.
        $this->assertInstanceOf(PostgresAdapter::class, $dbAdapter);

        // Case 2. Use Late Static Binding.
        $this->assertEquals('bar.foo', BarClass::bar1());

        // Case 3. Dont use Late Static Binding by using `self::`.
        $this->assertEquals('foo.foo', BarClass::bar2());

        // Case 4. Call static method from abstract class.
        $this->assertEquals('foo.foo', FooAbstract::foo());

        // Case 5. Call parent method by using `parent::`.
        $this->assertEquals('foo.bar3', FooAbstract::bar3());
    }

    /**
     * Create API
     * Task 8. For this final question, you are going to write some code that shows an understanding of the
     * concept of abstraction in PHP and OOP. The idea here is to create an abstraction layer for an
     * API endpoint. To explain further:
     *  a. Please create a class or interface that functions as an abstract layer for an API
     *  endpoint
     *  b. Please then create a class that implements the abstraction layer. The purpose of this
     *  class should be to function as the API endpoint for where game feedback will be
     *  submitted, and ultimately stored within your feedback table. The API will receive a
     *  HTTP POST request, with a JSON payload like so:
     *    {
     *      gamePlayerSessionId: [the id of the game player session]
     *      feedback: [the feedback submitted by the player]
     *    }
     * @group api
     */
    public function testAPI()
    {
        $client = $this->client();
        $client->connect();

        $client->query('DROP TABLE IF EXISTS feedback');
        $client->query('
            CREATE TABLE "feedback" (
                id serial NOT NULL
                    CONSTRAINT feedback_pk
                        PRIMARY KEY,
                "gamePlayerSessionId" integer,
                value text
            );
        ');

        // Will use N-layered pattern: Request | Client | Service | TableGateway | Response
        $apiClient = new APIClient($this->config);
        $request = new CreateFeedbackRequest('POST', json_encode([
            'gamePlayerSessionId' => 1,
            'feedback' => 'some feedback',
        ]));
        $response = $apiClient->execute($request);

        $client->query('DROP TABLE feedback');
        $client->close();

        $this->assertEquals('{"id":"1","gamePlayerSessionId":1,"feedback":"some feedback"}', $response->getContent());
    }
}
