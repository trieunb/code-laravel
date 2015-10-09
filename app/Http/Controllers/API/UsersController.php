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
		$user_id = \Auth::user()->id;
		
		if ($user_id != $id) {
			return response()->json(['status' => 'access for denied'], 403);
		}
		if ( !$this->user->save($request, $user_id))
			return response()->json(['status' => false, 'message' => 'Error when save infromation user'], 500);

		if( !$this->user_education->save($request, $request->get('user_education_id'), $user_id)) {
			return response()->json(['status' => false, 'message' => 'Error when save information education user'], 500);
		}

		if ( !$this->user_work_history->save($request, $request->get('user_work_history_id'),  $user_id)) {
			return response()->json(['status' => false, 'message' => 'Error when save information work history'], 500);
		}

		if ( !$this->user_skill->save($request, $request->get('user_skill_id'), $user_id)) {
			return response()-json(['status' => false, 'message' => 'Error when save information skill of user']);
		}

		return response()->json(['status' => true], 200);
	}
}
