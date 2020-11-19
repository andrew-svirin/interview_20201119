<?php

namespace AndrewSvirin\Interview\Services;

use AndrewSvirin\Interview\Gateways\FeedbackTableGateway;

/**
 * Class FeedbackService
 * Process feedback model.
 */
class FeedbackService extends ModelService
{

    /**
     * @var FeedbackTableGateway
     */
    private $tableGateway;

    public function __construct(FeedbackTableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Create feedback and save in storage.
     * @param array $feedback
     * @return array
     */
    public function create(array $feedback): array
    {
        return $this->tableGateway->save($feedback);
    }
}
