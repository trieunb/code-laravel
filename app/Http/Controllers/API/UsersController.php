<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\EditProfileRequest;
use App\Repositories\UserEducation\UserEducationInterface;
use App\Repositories\UserSKill\UserSkillInterface;
use App\Repositories\UserWorkHistory\UserWorkHistoryInterface;
use App\Repositories\User\UserInterface;
use App\ValidatorApi\UserEducation_Rule;
use App\ValidatorApi\UserWorkHistory_Rule;
use App\ValidatorApi\User_Rule;
use App\ValidatorApi\ValidatorAPiException;
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

	public function editProfile($id, Request $request, 
		User_Rule $user_rule, 
		UserWorkHistory_Rule $user_work_history_rule,
		UserEducation_Rule $user_education_rule
	) {
		$data = $request->all();
		$user = \JWTAuth::toUser($data['token']);

		if ($user->id != $id) {
			return response()->json(['status' => 'access for denied'], 403);
		}

		try {
			$user_rule->validate($data['user'], $user->id);	
		} catch (ValidatorAPiException $e) {
			return response()->json(['status' => false, 'message' => $e->getErrors()]);
		}
		try {
			$user_work_history_rule->validate($data['user_educations']);
		} catch (ValidatorAPiException $e) {
			return response()->json(['status' => false, 'message' => $e->getErrors()]);
		}
		try {
			$user_education_rule->validate($data['user_work_histories']);
		} catch (ValidatorAPiException $e) {
			return response()->json(['status' => false, 'message' => $e->getErrors()]);
		}

		$this->user->saveFromApi($data['user'], $user->id);
		$this->user_education->saveFromApi($data['user_educations'], $user->id);
		$this->user_work_history->saveFromApi($data['user_work_histories'], $user->id);
		$this->user_skill->saveFromApi($data['user_skills'],  $user->id);

		return response()->json(['status' => true], 200);
	}
}
