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

	public function editProfile($id, EditProfileRequest $request)
	{
		$data = json_decode($request->get('data'), true);
		dd($data);
		$token = \JWTAuth::getToken($data['access_token']);
		$user = \JWTAuth::getUser($token);

		if ($user->id != $id) {
			return response()->json(['status' => 'access for denied'], 403);
		}

		if ( !$this->user->save($data['user'], $user->id)) {
			return response()->json(['status' => false, 'message' => 'Error when save infromation user'], 500);
		}

		if( !$this->user_education->save($data['user_education'], $data['user_education']['id'], $user->id)) {
			return response()->json(['status' => false, 'message' => 'Error when save information education user'], 500);
		}

		if ( !$this->user_work_history->save($data['user_work_history'], 
			$data['user_work_history']['user_work_history_id'], 
			$user->id)) {
			return response()->json(['status' => false, 'message' => 'Error when save information work history'], 500);
		}

		if ( !$this->user_skill->save($data['user_skill'], $data['user_skill']['user_skill_id'], $user->id)) {
			return response()-json(['status' => false, 'message' => 'Error when save information skill of user']);
		}

		return response()->json(['status' => true], 200);
	}
}
