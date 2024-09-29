<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\Interest;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        $faculties = [
            'Цифрових, освітніх та соціальних технологій',
            'Аграрних технологій та екології',
            'Митної справи, матеріалів та технологій',
            'Транспорту та механічної інженерії',
            'Комп’ютерних та інформаційних технологій',
            'Бізнесу та права',
            'Архітектури, будівництва та дизайну',
        ];

        foreach ($faculties as $faculty) {
            Faculty::create([
                'name' => $faculty,
            ]);
        }

        $interests = [
            'Спорт',
            'Ігри',
            'Знайомство',
            'Оренда житла',
        ];

        foreach ($interests as $interest) {
            Interest::create([
                'name' => $interest,
            ]);
        }
    }
}
