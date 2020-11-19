<?php

namespace AndrewSvirin\Interview\Adapters;

/**
 * Interface OutputAdapterInterface
 * Implements output elements interface.
 */
interface OutputAdapterInterface
{

    /**
     * Render table.
     * @param array $header
     * @param array $rows
     * @param string|null $caption
     * @return string
     */
    public function table(array $header, array $rows, string $caption = null): string;

    /**
     * Render Panel.
     * @param string $label
     * @param string $value
     * @param string|null $caption
     * @return string
     */
    public function panel(string $label, string $value, string $caption = null): string;
}
