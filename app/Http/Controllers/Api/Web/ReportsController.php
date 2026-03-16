<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Models\Api\WebinarReport ;

class ReportsController extends Controller
{

   /**
    * Get report reasons (for reporting a course).
    *
    * @OA\Get(
    *     path="/v1/courses/reports/reasons",
    *     summary="List report reasons",
    *     tags={"Courses"},
    *     @OA\Response(response=200, description="List of report reasons")
    * )
    */
   public function index(){

    $reasons=getReportReasons() ;
    return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),$reasons);

   }


}
