<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserJob;  // ← ADD THIS LINE (1)
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponder;

class UserController extends Controller
{
    use ApiResponder;
    
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Get all users
     */
    public function index()
    {
        $users = User::all();
        return $this->successResponse($users);
    }
    
    /**
     * Get single user by ID
     */
    public function show($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);
        }
        
        return $this->successResponse($user);
    }
    
    /**
     * Create new user
     */
    public function add(Request $request)
    {
        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'gender' => 'required|in:Male,Female',
            'jobid' => 'required|numeric|min:1'
        ];
        
        $this->validate($request, $rules);
        
        // Validate if jobid exists in tbluserjob (2)
        UserJob::findOrFail($request->jobid);
        
        $user = User::create($request->all());
        
        return $this->successResponse($user, Response::HTTP_CREATED);
    }
    
    /**
     * Update existing user
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'username' => 'max:20',
            'password' => 'max:20',
            'gender' => 'in:Male,Female',
            'jobid' => 'numeric|min:1'  // ← ADD THIS LINE (3)
        ];
        
        $this->validate($request, $rules);
        
        $user = User::find($id);
        
        if (!$user) {
            return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);
        }
        
        // If jobid is being updated, validate it exists (4)
        if ($request->has('jobid')) {
            UserJob::findOrFail($request->jobid);
        }
        
        $user->fill($request->all());
        
        if ($user->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $user->save();
        
        return $this->successResponse($user);
    }
    
    /**
     * Delete user
     */
    public function delete($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);
        }
        
        $user->delete();
        
        return $this->successResponse($user);
    }
}