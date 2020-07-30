<?php

namespace App\Repositories;

use App\UserProfile;

/**
 * 
 */
class UserProfileDetails
{
	public function profileDetails($request)
	{
        $user_id = $request->user()->id;

        $profile_details = UserProfile::where('user_id',$user_id)->select('first_name', 'last_name', 'address')->first();

        return $profile_details;
	}
}