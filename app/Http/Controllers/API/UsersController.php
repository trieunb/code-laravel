<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\EditProfileRequest;
use App\Repositories\Objective\ObjectiveInterface;
use App\Repositories\Template\TemplateInterface;
use App\Repositories\UserEducation\UserEducationInterface;
use App\Repositories\UserSkill\UserSkillInterface;
use App\Repositories\UserWorkHistory\UserWorkHistoryInterface;
use App\Repositories\User\UserInterface;
use App\ValidatorApi\Objective_Rule;
use App\ValidatorApi\UserEducation_Rule;
use App\ValidatorApi\UserSkill_Rule;
use App\ValidatorApi\UserWorkHistory_Rule;
use App\ValidatorApi\User_Rule;
use App\ValidatorApi\ValidatorAPiException;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

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

	protected $template;
	
	protected $objective;

	public function __construct(UserInterface $user, 
		UserEducationInterface $user_education,
		UserWorkHistoryInterface $user_work_history,
		UserSkillInterface $user_skill,
		TemplateInterface $template,
		ObjectiveInterface $objective
	) {
		$this->middleware('jwt.auth');

		$this->user = $user;
		$this->user_education = $user_education;
		$this->user_work_history = $user_work_history;
		$this->user_skill = $user_skill;
		$this->template = $template;
		$this->objective = $objective;
	}

	public function getProfile(Request $request)
	{
		$user = \JWTAuth::toUser($request->get('token'));
		
		return response()->json([
			'status_code' => 200, 'status' => true, 'data' => $this->user->getProfile($user->id)
		]);
	}

	public function postProfile($id, Request $request, 
		User_Rule $user_rule, 
		UserWorkHistory_Rule $user_work_history_rule,
		UserEducation_Rule $user_education_rule,
		UserSkill_Rule $user_skill_rule,
		Objective_Rule $objective_rule
	) {
		$user = \JWTAuth::toUser($request->get('token'));
		if ($user->id != $id) {
			return response()->json(['status_code' => 403,'status' => false, 'message' => 'access for denied'], 403);
		}
		
		if ($request->has('user')) {
			try {
				$user_rule->validate($request->get('user'), $user->id);	
				$this->user->saveFromApi($request->get('user'), $user->id);
			} catch (ValidatorAPiException $e) {
				return response()->json(['status_code' => 412, 'status' => false, 'message' => $e->getErrors()], 412);
			}
		}

		if ($request->has('user_educations')) {
			try {
				if (count($request->get('user_educations')) > 1) {
					foreach ($request->get('user_educations') as $user_education_data) {
							$user_education_rule->validate($user_education_data);
					}
				} else {
					$user_education_rule->validate($request->get('user_educations'));
				}

				$this->user_education->saveFromApi($request->get('user_educations'), $user->id);
			} catch (ValidatorAPiException $e) {
				return response()->json(['status_code', 412, 'status' => false, 'message' => $e->getErrors()], 412);
			}	
		}
		
		if ($request->has('user_work_histories')) {
			try {
				if (count($request->get('user_work_histories')) > 1) {
					foreach ($request->get('user_work_histories') as $user_work_history_data) {
						$user_work_history_rule->validate($user_work_history_data);
					}
				} else {
					$user_work_history_rule->validate($request->get('user_work_histories'));	
				}

				$this->user_work_history->saveFromApi($request->get('user_work_histories'), $user->id);
			} catch (ValidatorAPiException $e) {
				return response()->json(['status_code', 412, 'status' => false, 'message' => $e->getErrors()], 412);
			}
		}
		
		if ($request->has('user_skills')) {
			try {
				if (count($request->get('user_skills')) > 1) {
					foreach ($request->get('user_skills') as $user_skill_data) {
						$user_skill_rule->validate($user_skill_data);
					}	
				}else {
					$user_skill_rule->validate($request->get('user_skills'));
				}		

				$this->user_skill->saveFromApi($request->get('user_skills'),  $user->id);
			} catch (ValidatorAPiException $e) {
				return response()->json(['status_code', 412, 'status' => false, 'message' => $e->getErrors()], 412);
			}
		}

		if ($request->has('objectives')) {
			try {
				if (count($request->get('objectives')) > 1) {
					foreach ($request->get('objectives') as $objective) {
						$objective_rule->validate($objective);
					}
				} else {
					$objective_rule->validate($request->get('objectives'));
				}

				$this->objective->saveFromApi($request->get('objectives'), $id);
			} catch (ValidatorAPiException $e) {
				return response()->json(['status_code', 412, 'status' => false, 'message' => $e->getErrors()], 412);
			}
		}
		
		return response()->json(['status_code' => 200, 'status' => true]);
	}

	public function getTemplates(Request $request)
	{
		$user = \JWTAuth::toUser($request->get('token'));
		return response()->json([
			'status_code' => 200,
			'status' => true,
			'data' => $this->user->getTemplateFromUser($user->id)
		]); 
	}

	public function uploadImage($id, Request $request)
	{
		$user = \JWTAuth::toUser($request->get('token'));

		if ($user->id != $id) {
			return response()->json(['status_code' => 403,'status' => false, 'message' => 'access for denied'], 403);
		}

		try {
			$this->validate($request, ['avatar' => 'image',]);	
		} catch (ValidationException $e) {
			return response()->json([
				'status_code' => 412, 'status' => false, 'message' => $e->getErrors()],
			412);
		}
		try {
			return $this->user->uploadImage($request->file('avatar'), $user->id)
			? response()->json(['status_code' => 200, 'status' => true])
			: response()->json(['status_code' => 500, 'status' => false, 'message' => 'Error when Upload Image']);
		} catch(UploadException $e) {
			return response()->json(['status_code' => 500, 'status' => false, 'message' => $e->getErrorMessage()]);
		}
		
	}
}
