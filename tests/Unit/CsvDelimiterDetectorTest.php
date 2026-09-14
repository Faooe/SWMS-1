<?php

namespace Tests\Unit;

use App\Support\CsvDelimiterDetector;
use Tests\TestCase;

class CsvDelimiterDetectorTest extends TestCase
{
    public function test_it_detects_semicolon_and_rewinds_stream(): void
    {
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, "name;email\nBudi;budi@example.com\n");
        rewind($handle);

        $this->assertSame(';', app(CsvDelimiterDetector::class)->detect($handle));
        $this->assertSame('name;email', trim((string) fgets($handle)));

        fclose($handle);
    }

    public function test_it_defaults_to_comma_for_empty_stream(): void
    {
        $handle = fopen('php://memory', 'r+');

        $this->assertSame(',', app(CsvDelimiterDetector::class)->detect($handle));

        fclose($handle);
    }
}
