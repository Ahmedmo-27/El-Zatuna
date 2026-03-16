<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SectionsTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);

        $this->call(UniversitiesAndFacultiesTableSeeder::class);

        $this->call(SubjectCategoriesTableSeeder::class);

        $this->call(PaymentChannelsTableSeeder::class);

        $this->call(LandingBuilderComponentsSeeder::class);

        $this->call(ThemeHeaderFooterSeeder::class);

        $this->call(DefaultThemeSeeder::class);

        $this->call(HomeLandingSeeder::class);

        $this->call(FreeCoursesSeeder::class);
        $this->call(FreeCoursesContentSeeder::class);

        // $this->call(DemoDataSeeder::class);
    }
}
