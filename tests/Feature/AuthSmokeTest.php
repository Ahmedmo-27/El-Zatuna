<?php

namespace Tests\Feature;

use App\Http\Middleware\Share;
use App\Http\Middleware\UserLocale;
use App\Http\Middleware\CheckMobileApp;
use Tests\TestCase;

class AuthSmokeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Share middleware hits DB tables not available in sqlite tests.
        $this->withoutMiddleware([
            Share::class,
            UserLocale::class,
            CheckMobileApp::class,
        ]);
    }

    public function test_login_page_loads(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_register_page_loads(): void
    {
        $this->get('/register')->assertStatus(200);
    }

    public function test_register_step_one_page_loads(): void
    {
        $this->get('/register/step/1')->assertStatus(200);
    }
}
