<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_public_motor_api_returns_a_successful_response(): void
    {
        $response = $this->getJson('/api/motors');

        $response->assertOk();
    }
}
