<?php

namespace AndrewSvirin\Interview\Factories;

use AndrewSvirin\Interview\Responses\APIResponse;

/**
 * Class APIResponseFactory
 * Factory for @see \AndrewSvirin\Interview\Responses\APIResponse
 */
class APIResponseFactory
{

    /**
     * Produce api response.
     * @param string $className
     * @param array $data
     * @return APIResponse
     */
    public static function produce(string $className, array $data): APIResponse
    {
        $result = new $className(json_encode($data));

        return $result;
    }
}
