<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        if (config('database.default') === 'sqlite') {
            $this->markTestSkipped('Example web smoke test requires full MySQL-backed app settings.');
        }

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
    }
}
