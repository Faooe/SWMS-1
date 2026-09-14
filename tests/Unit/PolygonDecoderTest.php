<?php

namespace Tests\Unit;

use App\Support\PolygonDecoder;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PolygonDecoderTest extends TestCase
{
    public function test_it_normalizes_indexed_and_named_points(): void
    {
        $points = app(PolygonDecoder::class)->decode(json_encode([
            ['lat' => -3.3, 'lng' => 114.6],
            [-3.31, 114.61],
            ['lat' => '-3.32', 'lng' => '114.62'],
        ]));

        $this->assertSame([
            [-3.3, 114.6],
            [-3.31, 114.61],
            [-3.32, 114.62],
        ], $points);
    }

    public function test_it_rejects_polygons_with_too_few_points(): void
    {
        $this->expectException(ValidationException::class);

        app(PolygonDecoder::class)->decode(json_encode([[-3.3, 114.6]]));
    }
}
