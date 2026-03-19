<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitiesAndFacultiesTableSeeder extends Seeder
{
    /**
     * Universities and their faculties from the provided list.
     * Each university name maps to an array of faculty names offered at that university.
     */
    private function getUniversityFacultyPairs(): array
    {
        return [
            'AAST'    => ['Engineering'],
            'MIU'     => ['Computer Science', 'Business', 'Pharmacy'],
            'GUC'     => ['Engineering', 'Business', 'Pharmacy'],
            'Coventry'=> ['Engineering'],
            'EUI'     => ['Engineering'],
            'BUE'     => ['Engineering', 'Computer Science'],
            'MSA'     => ['Engineering'],
            'ECU'     => ['Business', 'Computer Science'],
            'GIU'     => ['Engineering'],
            'CIC'     => ['Engineering'],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->getUniversityFacultyPairs() as $universityName => $facultyNames) {
            $university = University::updateOrCreate(
                ['name' => $universityName],
                ['name' => $universityName]
            );

            foreach ($facultyNames as $facultyName) {
                Faculty::updateOrCreate(
                    [
                        'university_id' => $university->id,
                        'name'          => $facultyName,
                    ],
                    [
                        'university_id' => $university->id,
                        'name'          => $facultyName,
                    ]
                );
            }
        }
    }
}
