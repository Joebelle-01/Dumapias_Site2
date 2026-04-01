<?php
namespace App\Http\Controllers;

use App\Models\UserJob;
use Illuminate\Http\Response;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;

class UserJobController extends Controller
{
    use ApiResponder;
    
    /**
     * Get all jobs
     */
    public function index()
    {
        $jobs = UserJob::all();
        return $this->successResponse($jobs);
    }
    
    /**
     * Get single job by ID
     */
    public function show($id)
    {
        $job = UserJob::find($id);
        
        if (!$job) {
            return $this->errorResponse('Job not found', Response::HTTP_NOT_FOUND);
        }
        
        return $this->successResponse($job);
    }
}