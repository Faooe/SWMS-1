<?php

namespace App\Support;

/** Detects the delimiter used by a CSV stream without consuming it. */
class CsvDelimiterDetector
{
    /** @param resource $handle */
    public function detect($handle): string
    {
        $firstLine = fgets($handle);
        rewind($handle);

        if ($firstLine === false) {
            return ',';
        }

        return substr_count($firstLine, ';') > substr_count($firstLine, ',')
            ? ';'
            : ',';
    }
}
