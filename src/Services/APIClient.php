<?php

namespace AndrewSvirin\Interview\Services;

use AndrewSvirin\Interview\Adapters\PostgresAdapter;
use AndrewSvirin\Interview\Factories\APIResponseFactory;
use AndrewSvirin\Interview\Gateways\FeedbackTableGateway;
use AndrewSvirin\Interview\Requests\APIRequest;
use AndrewSvirin\Interview\Requests\Feedback\CreateFeedbackRequest;
use AndrewSvirin\Interview\Responses\APIResponse;
use AndrewSvirin\Interview\Responses\FeedbackResponse;
use LogicException;

class APIClient
{

    /**
     * @var array = [
     *   'database' => 'array',
     * ]
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param APIRequest $request
     * @return APIResponse
     */
    public function execute(APIRequest $request): APIResponse
    {
        [$serviceClass, $method, $responseClass] = $this->router($request);

        if (!method_exists($serviceClass, $method)) {
            throw new LogicException('Service `%s` method `%s` incorrect.', $serviceClass, $method);
        }

        // Here should be used Container with DI.
        $dbAdapter = new PostgresAdapter();
        $dbClient = new DBClient($dbAdapter, $this->config['database']);
        $tableGateway = new FeedbackTableGateway($dbClient);
        $service = new $serviceClass($tableGateway);

        // This logic should be located in controller.
        $requestResult = $service->{$method}($request->validated());

        return APIResponseFactory::produce($responseClass, $requestResult);
    }

    /**
     * Map request with service method.
     * @param APIRequest $request
     * @return string[]
     */
    private function router(APIRequest $request): array
    {
        switch (get_class($request)) {
            case CreateFeedbackRequest::class:
                $result = [
                    FeedbackService::class,
                    'create',
                    FeedbackResponse::class
                ];
                break;
            default:
                throw new LogicException('Incorrect request `%s`', get_class($request));
        }

        return $result;
    }
}
