<?php

namespace AndrewSvirin\Interview\Builders;

class OutputBuilder
{

    /**
     * List of elements.
     * @var array
     */
    private $elements;

    /**
     * Add raw element.
     * @param string $element
     * @return $this
     */
    public function addElement(string $element)
    {
        $this->elements[] = $element;

        return $this;
    }

    public function output(string $glue = null)
    {
        return implode($glue ?? '', $this->elements);
    }
}
