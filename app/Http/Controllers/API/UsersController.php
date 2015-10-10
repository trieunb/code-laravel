<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\EditProfileRequest;
use App\Repositories\UserEducation\UserEducationInterface;
use App\Repositories\UserSKill\UserSkillInterface;
use App\Repositories\UserWorkHistory\UserWorkHistoryInterface;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;

class UsersController extends Controller
{
	/**
	 * UserInterface
	 * @var [type]
	 */
	protected $user;

	/**
	 * UserEducationInterface
	 * @var [type]
	 */
	protected $user_education;

	/**
	 * UserWorkHistoryInterface
	 * @var [type]
	 */
	protected $user_work_history;
	
	/**
	 * UserSkillInterface
	 * @var [type]
	 */
	protected $user_skill;

	public function __construct(UserInterface $user, 
		UserEducationInterface $user_education,
		UserWorkHistoryInterface $user_work_history,
		UserSkillInterface $user_skill
	) {
		$this->middleware('jwt.auth');

		$this->user = $user;
		$this->user_education = $user_education;
		$this->user_work_history = $user_work_history;
		$this->user_skill = $user_skill;
	}

	public function editProfile($id, Request $request)
	{
		$data = $request->all();
		$user = \JWTAuth::getUser($data['access_token']);



		if ($user->id != $id) {
			return response()->json(['status' => 'access for denied'], 403);
		}
		$rules_user = [
			'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'avatar' => 'image',
            'dob' => 'required',
            'city' => 'required', 
            'state' => 'required'
        ];
        $rules_user_education = [
        	'school_name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'degree' => 'required',
            'result' => 'required'
        ];
        $rules_user_work_history = [
        	'company' => 'required',
            'work_history_start' => 'required',
            'work_history_end' => 'required',
            'job_title' => 'required',
            'job_description' => 'required'
        ];

		if (\Validator::make($data['user'], $rules_user)->fails())
			return response()->json(['status' => false, 'message' => 'Information user invalid'], 402); 

		if (\Validator::make($data['user_educations'], $rules_user_education)->fails())
			return response()->json(['status' => false, 'message' => 'Information user education invalid'], 402); 

		if (\Validator::make($data['user_work_histories'], $rules_user_work_history)->fails())
			return response()->json(['status' => false, 'message' => 'Information user work history invalid'], 402); 

		if ( !$this->user->saveFromApi($data['user'], $user->id)) {
			return response()->json(['status' => false, 'message' => 'Error when save infromation user'], 500);
		}

		$this->user_education->saveFromApi($data['user_educations'], $user->id);
		$this->user_work_history->saveFromApi($data['user_work_histories'], $user->id);
		$this->user_skill->saveFromApi($data['user_skills'],  $user->id);

		return response()->json(['status' => true], 200);
	}
}
