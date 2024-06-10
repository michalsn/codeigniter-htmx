<?php

namespace Michalsn\CodeIgniterHtmx\Format;

use CodeIgniter\Format\FormatterInterface;
use CodeIgniter\View\Table;

/**
 * HTML data formatter
 */
class HTMLFormatter implements FormatterInterface
{
    /**
     * Takes the given data and formats it.
     *
     * @param array|string|null $data
     */
    public function format($data): string
    {
        if ($data === null || $data === '' || $data === []) {
            return '';
        }

        if (is_array($data)) {
            if (array_keys($data) === ['status', 'error', 'messages']) {
                if (isset($data['messages']['error']) && count($data['messages']) === 1) {
                    return $data['messages']['error'];
                }

                $data = $data['messages'];
            }

            $data = $this->formatTable($data);
        }

        return $data;
    }

    /**
     * Format data as a table.
     *
     * @param mixed $data
     */
    protected function formatTable($data): string
    {
        if (isset($data[0]) && count($data) !== count($data, COUNT_RECURSIVE)) {
            // Multi-dimensional array
            $headings = array_keys($data[0]);
        } else {
            // Single array
            $headings = array_keys($data);
            $data     = [$data];
        }

        $table = new Table();
        $table->setHeading($headings);

        foreach ($data as $row) {
            // Suppressing the "array to string conversion" notice
            // Keep the "evil" @ here
            $row = @array_map('strval', $row);

            $table->addRow($row);
        }

        return $table->generate();
    }
}
