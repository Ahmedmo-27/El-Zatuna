<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Translation\CategoryTranslation;
use Illuminate\Database\Seeder;

class SubjectCategoriesTableSeeder extends Seeder
{
    /**
     * Academic subjects to insert as top-level categories (no duplicates).
     */
    private function getSubjects(): array
    {
        return [
            'Circuits 1',
            'Circuits 2',
            'Electric machine',
            'Intro to programming',
            'Chemistry',
            'Physics',
            'Calculus 1',
            'Statics',
            'Physics 1',
            'Calculus 2',
            'Calculus 3',
            'Dynamics',
            'Differential',
            'Accounting 1',
            'Accounting 2',
            'Math 101',
            'Economics 1',
            'Economics 2',
            'Statistics',
            'Physics 2',
            'Microeconomics',
            'Math',
            'Intro to CS',
            'Mechanics',
            'Math 1',
            'Electrical 1',
            'Signals',
            'Sensors',
            'Electrical 2',
            'Electronics 2',
            'Inorganic',
            'Cogno',
            'Terminology',
            'Pharmaceutical bio',
            'Anatomy',
            'Pharmaceutical',
            'Analytical',
            'Organic',
            'Algebra',
            'Classic Control',
            'Power electronics',
            'Discrete Math',
            'Intro to computing',
            'Mechanics 2',
            'Math 2',
            'CS',
            'Programming 1',
            'Programming 2',
            'Digital Logic',
            'Multivariate',
            'Pathology',
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locale = 'en';
        $order = (int) Category::whereNull('parent_id')->max('order');

        foreach ($this->getSubjects() as $title) {
            $title = trim($title);
            if ($title === '') {
                continue;
            }

            $existing = CategoryTranslation::where('locale', $locale)
                ->where('title', $title)
                ->first();

            if ($existing) {
                continue;
            }

            $order++;
            $category = Category::create([
                'parent_id' => null,
                'slug'      => Category::makeSlug($title),
                'order'     => $order,
            ]);

            CategoryTranslation::create([
                'category_id' => $category->id,
                'locale'      => $locale,
                'title'       => $title,
            ]);
        }

        cache()->forget(Category::$cacheKey);
    }
}
