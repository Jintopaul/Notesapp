<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserProfileDetails;
use App\Repositories\UserNotes;
use App\User;
use App\UserProfile;
use Validator;

class AuthController extends Controller
{
	protected $profile;

	protected $user_notes;

    /**
     * AuthController constructor.
     *
     * @param UserProfileDetails $profile
     */
    public function __construct(UserProfileDetails $profile, UserNotes $user_notes)
    {
        $this->profile = $profile;

        $this->user_notes = $user_notes;
    }



    /**
     * signup
     *
     * @param  [string] first_name
     * @param  [string] last_name
     * @param  [string] email
     * @param  [string] password
     * @param  [text] address
     */
    public function signup(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'address' => 'required|string',
        	]);

	        if($validator->fails())
	        {
        		return response()->json(['message' => 'Validation Error!','more_details' => $validator->errors(), "status" => 422]);
	        }
	        else
	        {
	        	$user = new User();
	        	$user->email = $request->email;
	        	$user->password = bcrypt($request->password);
 				
 				if($user->save())
 				{
 					$user_profile = new UserProfile();
 					$user_profile->user_id = $user->id;
 					$user_profile->first_name = $request->first_name;
 					$user_profile->last_name = $request->last_name;
 					$user_profile->address = $request->address;
 					$user_profile->save();

 					$access_token =  'Bearer '.$user->createToken('Laravel Personal Access Client')->accessToken;
 					
 					return response()->json(['message' => 'Successfully created user!', 'access_token' => $access_token, "status" => 201]);
 				}
	        }       
    	}
    	catch(\Exception $e)
    	{
    		return response()->json(['message' => $e->getMessage(), "status" => 500]);
    	}
    }


    /**
     * login
     *
     * @param  [string] email
     * @param  [string] password
     */
    public function login(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        	]);

	        if($validator->fails())
	        {
        		return response()->json(['message' => 'Validation Error!','more_details' => $validator->errors(), "status" => 422]);
	        }
	        else
	        {
        		$credentials = request(['email', 'password']);

        		if(!Auth::attempt($credentials))
        		{
        			return response()->json(['message' => 'Unauthorized', "status" => 401]);
        		}

        		$user = $request->user();
        		$access_token =  'Bearer '.$user->createToken('Laravel Personal Access Client')->accessToken;

        		return response()->json(['message' => 'Success!', 'access_token' => $access_token, "status" => 200]);
	        }
    	}
    	catch(\Exception $e)
    	{
    		return response()->json(['message' => $e->getMessage(), "status" => 500]);
    	}
    }


    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        if($request->user()->token()->revoke())
        {
        	return response()->json(['message' => 'Successfully logged out','status'=> 200]);
        }
        else
        {
        	return response()->json(['message' => 'Unauthorized','status'=> 401]);
        }
    }


    /**
    *
    *
    */
    public function unauthenticated()
    {
    	return response()->json(['message' => 'Unauthorized','status'=> 400]);
    }


    /**
    *
    *
    */
    public function user(Request $request)
    {
    	$profile_details = $this->profile->profileDetails($request);
        return response()->json($profile_details);
    }


    /**
    *
    *
    */
    public function storeNote(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
            'text' => 'required|string',
            'file' => 'sometimes|file'
        	]);

	        if($validator->fails())
	        {
        		return response()->json(['message' => 'Validation Error!','more_details' => $validator->errors(), "status" => 422]);
	        }
	        else
	        {
	        	$response = $this->user_notes->store($request);

	        	if($response)
	        	{
        			return response()->json(['message' => 'Successfully created','status'=> 201]);
	        	}
	        	else
	        	{
        			return response()->json(['message' => 'Something gone wrong','status'=> 500]);
	        	}

	        }
	    }
    	catch(\Exception $e)
    	{
    		return response()->json(['message' => $e->getMessage(), "status" => 500]);
    	}
    }

    public function getNotes(Request $request)
    {
    	$notes = $this->user_notes->index($request);
        return response()->json($notes);
    }

    public function getNote(Request $request, $id)
    {
    	$note = $this->user_notes->note($request, $id);
        return response()->json($note);
    }

    public function updateNote(Request $request, $id)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
            'text' => 'required|string',
        	]);

	        if($validator->fails())
	        {
        		return response()->json(['message' => 'Validation Error!','more_details' => $validator->errors(), "status" => 422]);
	        }
	        else
	        {
    			$response = $this->user_notes->update($request, $id);


	        	if($response)
	        	{
        			return response()->json(['message' => 'Successfully updated','status'=> 201]);
	        	}
	        	else
	        	{
        			return response()->json(['message' => 'Something gone wrong','status'=> 500]);
	        	}

	        }
	    }
    	catch(\Exception $e)
    	{
    		return response()->json(['message' => $e->getMessage(), "status" => 500]);
    	}
    }

    public function destroyNote(Request $request, $id)
    {
        try 
        {
    		$response = $this->user_notes->destroy($request, $id);

    		if($response)
        	{
    			return response()->json(['message' => 'Note deleted successfully.','status'=> 200]);
        	}
        	else
        	{
    			return response()->json(['message' => 'Something gone wrong','status'=> 500]);
        	}

        } 
        catch (\Exception $e) 
        {
            return response()->json(['message' => $e->getMessage(), 'status'=> 500]);
        }
    }
}
