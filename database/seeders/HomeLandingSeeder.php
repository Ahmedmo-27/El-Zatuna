<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomeLandingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $landingId = DB::table('landings')->where('url', 'home')->value('id');

        if (empty($landingId)) {
            $landingId = DB::table('landings')->insertGetId([
                'url' => 'home',
                'preview_img' => null,
                'enable' => true,
                'created_at' => time(),
            ]);
        }

        DB::table('landing_translations')->updateOrInsert(
            [
                'landing_id' => $landingId,
                'locale' => 'en',
            ],
            [
                'title' => 'Home',
            ]
        );

        $componentId = DB::table('landing_builder_components')->where('name', 'center_text')->value('id');

        if (!empty($componentId)) {
            $landingComponentId = DB::table('landing_components')
                ->where('landing_id', $landingId)
                ->where('component_id', $componentId)
                ->value('id');

            if (empty($landingComponentId)) {
                $landingComponentId = DB::table('landing_components')->insertGetId([
                    'landing_id' => $landingId,
                    'component_id' => $componentId,
                    'preview' => null,
                    'enable' => true,
                    'order' => 1,
                ]);
            }

            $content = json_encode([
                'main_content' => [
                    'pre_title' => 'Welcome',
                    'title' => 'Welcome to LMS',
                    'description' => 'Use the admin panel to change these landing components.',
                ],
            ], JSON_UNESCAPED_UNICODE);

            DB::table('landing_component_translations')->updateOrInsert(
                [
                    'landing_component_id' => $landingComponentId,
                    'locale' => 'en',
                ],
                [
                    'content' => $content,
                ]
            );
        }

        $defaultThemeId = DB::table('themes')->where('is_default', true)->value('id');

        if (!empty($defaultThemeId)) {
            DB::table('themes')->where('id', $defaultThemeId)->update([
                'home_landing_id' => $landingId,
                'enable' => true,
            ]);
        }
    }
}
