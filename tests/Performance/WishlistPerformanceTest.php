<?php

namespace Tests\Performance;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_simple(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

}
