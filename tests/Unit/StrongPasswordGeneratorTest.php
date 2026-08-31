<?php

namespace Tests\Unit;

use App\Support\StrongPasswordGenerator;
use PHPUnit\Framework\TestCase;

class StrongPasswordGeneratorTest extends TestCase
{
    public function test_generated_password_always_meets_phase_one_policy(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $password = StrongPasswordGenerator::generate();

            $this->assertSame(12, strlen($password));
            $this->assertTrue(StrongPasswordGenerator::meetsPolicy($password));
            $this->assertMatchesRegularExpression('/[A-Z]/', $password);
            $this->assertMatchesRegularExpression('/[a-z]/', $password);
            $this->assertMatchesRegularExpression('/[0-9]/', $password);
        }
    }

    public function test_legacy_six_character_password_does_not_meet_new_creation_policy(): void
    {
        $this->assertFalse(StrongPasswordGenerator::meetsPolicy('Abc123'));
    }
}
