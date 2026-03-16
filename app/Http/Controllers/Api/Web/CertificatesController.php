<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Controller;
use App\Models\Api\Certificate;
use Illuminate\Http\Request;

class CertificatesController extends Controller
{
    /**
     * Validate a certificate by ID (public verification).
     *
     * @OA\Get(
     *     path="/v1/certificate_validation",
     *     summary="Validate certificate",
     *     tags={"Courses"},
     *     @OA\Parameter(name="certificate_id", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="is_valid and certificate details or null")
     * )
     */
    public function checkValidate(Request $request)
    {
        validateParam($request->all(), [
            'certificate_id' => 'required|numeric',
        ]);

        $certificateId = $request->input('certificate_id');

        $certificate = Certificate::where('id', $certificateId)->first();

        if (!empty($certificate)) {
            $result = [
                'student' => $certificate->student->full_name,
                'webinar_title' => $certificate->quiz->webinar->title,
                'date' => dateTimeFormat($certificate->created_at, 'j F Y'),
            ];
            return apiResponse2(1, 'retrieved', 'api.public.retrieved',
                [
                    'is_valid' => true,
                    'certificate' => $certificate->details
                ]
            );
        }
        return apiResponse2(1, 'retrieved', 'api.public.retrieved',
            [
                'is_valid' => false,
                'certificate' => null
            ]
        );

    }
}
