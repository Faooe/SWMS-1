<?php

namespace Tests\Unit;

use App\Helpers\ResponseHelper;
use Tests\TestCase;

class ResponseHelperTest extends TestCase
{
    public function test_success_shape_is_consistent(): void
    {
        request()->attributes->set('request_id', 'shape-1');
        $payload = ResponseHelper::success(['ok' => true], 'Berhasil')->getData(true);

        $this->assertTrue($payload['success']);
        $this->assertSame('Berhasil', $payload['message']);
        $this->assertSame('shape-1', $payload['request_id']);
    }

    public function test_error_shape_is_consistent(): void
    {
        request()->attributes->set('request_id', 'shape-2');
        $payload = ResponseHelper::error('Gagal', ['field' => ['Invalid']], 422)->getData(true);

        $this->assertFalse($payload['success']);
        $this->assertSame('Gagal', $payload['message']);
        $this->assertSame('shape-2', $payload['request_id']);
    }
}
