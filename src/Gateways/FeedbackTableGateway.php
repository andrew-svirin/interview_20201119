<?php

namespace AndrewSvirin\Interview\Gateways;

use LogicException;

/**
 * Class FeedbackTableGateway
 * Operate with `feedback` table.
 */
class FeedbackTableGateway extends TableGateway
{

    const CREATE = 'INSERT INTO "feedback" ("gamePlayerSessionId", value) VALUES ($1, $2) RETURNING id';

    /**
     * Save data to storage.
     * @param array $data
     * @return array
     */
    public function save(array $data): array
    {
        $this->dbClient->connect();
        if (empty($data['id'])) {
            $queryResult = $this->dbClient->query(self::CREATE, [
                $data['gamePlayerSessionId'] ?? null,
                $data['feedback'] ?? null,
            ]);
        } else {
            throw new LogicException('Not predicted case for `update`.');
        }

        $result = [
            'id' => $queryResult[0]['id'],
            'gamePlayerSessionId' => $data['gamePlayerSessionId'] ?? null,
            'feedback' => $data['feedback'] ?? null,
        ];

        return $result;
    }
}
