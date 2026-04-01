<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponder;  // Add this line

class UserController extends Controller
{
    use ApiResponder;  // Add this line inside the class
    
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
        return $this->successResponse($users);  // Changed from response()->json()
    }
    
    /**
     * Get single user by ID
     */
    public function show($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);  // Changed
        }
        
        return $this->successResponse($user);  // Changed
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
        ];
        
        $this->validate($request, $rules);
        
        $user = User::create($request->all());
        
        return $this->successResponse($user, Response::HTTP_CREATED);  // Changed
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
        ];
        
        $this->validate($request, $rules);
        
        $user = User::find($id);
        
        if (!$user) {
            return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);  // Changed
        }
        
        $user->fill($request->all());
        
        if ($user->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);  // Changed
        }
        
        $user->save();
        
        return $this->successResponse($user);  // Changed
    }
    
    /**
     * Delete user
     */
    public function delete($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);  // Changed
        }
        
        $user->delete();
        
        return $this->successResponse($user);  // Changed
    }
}