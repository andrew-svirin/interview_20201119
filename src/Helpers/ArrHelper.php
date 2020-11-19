<?php

namespace AndrewSvirin\Interview\Helpers;

/**
 * Class ArrHelper
 * Helper functions to work with arrays.
 */
class ArrHelper
{
    /**
     * Return only filtered fields.
     * @param array $array
     * @param array $fields
     * @return array
     */
    public static function filter(array $array, array $fields): array
    {
        return array_filter(
            $array,
            function ($key) use ($fields) {
                return in_array($key, $fields);
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
