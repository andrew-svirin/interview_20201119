<?php

namespace AndrewSvirin\Interview\Requests;

use AndrewSvirin\Interview\Helpers\ArrHelper;

/**
 * Class APIRequest
 * Common API manipulations.
 *
 * @method array rules() Specify fields allowed in request.
 */
abstract class APIRequest extends Request
{

    /**
     * Get json data from body.
     * @return array|null
     */
    public function getJson(): ?array
    {
        $body = $this->getBody();
        return $body ? json_decode($body, true) : null;
    }

    /**
     * Get validated json data.
     */
    public function validated(): array
    {
        $json = $this->getJson();
        if (method_exists($this, 'rules')) {
            $rules = $this->rules();
            $result = ArrHelper::filter($json, $rules);
        } else {
            $result = $json;
        }

        return $result;
    }
}
