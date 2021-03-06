<?php

namespace AndrewSvirin\Interview\Requests\Feedback;

use AndrewSvirin\Interview\Requests\APIRequest;

class CreateFeedbackRequest extends APIRequest
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'gamePlayerSessionId',
            'feedback',
        ];
    }
}
