<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function facultiesByUniversity(Request $request, $universityId)
    {
        $faculties = Faculty::query()
            ->select('id', 'name')
            ->where('university_id', $universityId)
            ->orderBy('name')
            ->get();

        return response()->json([
            'code' => 200,
            'faculties' => $faculties,
        ]);
    }
}
