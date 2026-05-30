<?php

namespace Tests\Feature;

use App\Http\Middleware\Share;
use App\Http\Middleware\UserLocale;
use App\Http\Middleware\CheckMobileApp;
use Tests\TestCase;
use Illuminate\Support\Facades\View;

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

        View::share([
            'generalSettings' => [
                'rtl_languages' => [],
                'rtl_layout' => 0,
                'site_name' => '',
                'preloading' => false,
                'user_languages' => ['en'],
            ],
            'userThemeColorMode' => 'light',
            'currency' => 'EGP',
            'userCartCount' => 0,
            'userCarts' => [],
            'totalCartsPrice' => 0,
            'userCartDiscount' => 0,
            'themeHeaderData' => ['component_name' => null, 'contents' => []],
            'themeFooterData' => ['component_name' => null, 'contents' => []],
            'purchaseNotifications' => [],
            'floatingBar' => null,
            'categories' => collect(),
            'userDeviceType' => 'desktop',
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
        $this->get('/register/step/1')
            ->assertRedirect('/register');
    }
}
