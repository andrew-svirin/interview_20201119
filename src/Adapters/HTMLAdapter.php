<?php

namespace AndrewSvirin\Interview\Adapters;

class HTMLAdapter implements OutputAdapterInterface
{

    /**
     * @inheritDoc
     */
    public function table(array $header, array $rows, string $caption = null): string
    {
        $result = '<table>';

        // Add caption.
        if (null !== $caption) {
            $result .= '<caption>' . $caption . '</caption>';
        }

        // Add header.
        $result .= '<thead>';
        $result .= '<tr>';
        foreach ($header as $row) {
            $result .= '<th>' . $row . '</th>';
        }
        $result .= '</tr>';
        $result .= '</thead>';

        // Add body.
        $result .= '<tbody>';
        foreach ($rows as $row) {
            $result .= '<tr>';
            foreach ($row as $cell) {
                $result .= '<td>' . $cell . '</td>';
            }
            $result .= '</tr>';
        }
        $result .= '</tbody>';

        $result .= '</table>';

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function panel(string $label, string $value, string $caption = null): string
    {
        $result = '<panel>';

        // Add caption.
        if (null !== $caption) {
            $result .= '<h2>' . $caption . '</h2>';
        }

        // Add panel body.
        $result .= '<div>';
        $result .= '<h3>' . $label . '</h3>';
        $result .= ': ';
        $result .= '<b>' . $value . '</b>';
        $result .= '</div>';

        $result .= '</panel>';

        return $result;
    }
}
