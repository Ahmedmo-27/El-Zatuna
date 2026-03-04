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
    public function test_sitemap_index_endpoint_is_accessible_and_lists_child_sitemaps()
    {
        config(['app.key' => 'base64:' . base64_encode(random_bytes(32))]);
        $this->withoutMiddleware();

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee('/sitemaps/main.xml', false);
        $response->assertSee('/sitemaps/courses.xml', false);
        $response->assertSee('/sitemaps/blog.xml', false);
        $response->assertSee('/sitemaps/products.xml', false);
        $response->assertSee('/sitemaps/teachers.xml', false);
    }
}
