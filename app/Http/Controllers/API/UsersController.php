<?php

namespace App\Http\Controllers\API;

use App\Exceptions\NotFoundFieldIdException;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\EditProfileRequest;
use App\Repositories\Objective\ObjectiveInterface;
use App\Repositories\Qualification\QualificationInterface;
use App\Repositories\Reference\ReferenceInterface;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use App\Repositories\Template\TemplateInterface;
use App\Repositories\UserEducation\UserEducationInterface;
use App\Repositories\UserSkill\UserSkillInterface;
use App\Repositories\UserWorkHistory\UserWorkHistoryInterface;
use App\Repositories\User\UserInterface;
use App\ValidatorApi\Objective_Rule;
use App\ValidatorApi\Qualification_Rule;
use App\ValidatorApi\Reference_Rule;
use App\ValidatorApi\UserEducation_Rule;
use App\ValidatorApi\UserSkill_Rule;
use App\ValidatorApi\UserWorkHistory_Rule;
use App\ValidatorApi\User_Rule;
use App\ValidatorApi\ValidatorAPiException;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class UsersController extends Controller
{
	/**
	 * UserInterface
	 * @var $user
	 */
	protected $user;

	/**
	 * UserEducationInterface
	 * @var $user_education
	 */
	protected $user_education;

	/**
	 * UserWorkHistoryInterface
	 * @var $user_work_history
	 */
	protected $user_work_history;
	
	/**
	 * UserSkillInterface
	 * @var $user_skill
	 */
	protected $user_skill;

	/**
	 * TemplateInterface
	 * @var $template
	 */
	protected $template;
	
	/**
	 * TemplateMarketInterface
	 * @var $template_market
	 */
	protected $template_market;

	/**
	 * ObjectiveInterface
	 * @var $objective
	 */
	protected $objective;

	/**
	 * ReferenceInterface
	 * @var $reference
	 */
	protected $reference;

	private $qualification;

	public function __construct(UserInterface $user, 
		UserEducationInterface $user_education,
		UserWorkHistoryInterface $user_work_history,
		UserSkillInterface $user_skill,
		TemplateInterface $template,
		ObjectiveInterface $objective,
		ReferenceInterface $reference,
		TemplateMarketInterface $template_market,
		QualificationInterface $qualification
	) {
		$this->middleware('jwt.auth', ['except' => ['dataTable', 'getAnswersForAdmin']]);

		$this->user = $user;
		$this->user_education = $user_education;
		$this->user_work_history = $user_work_history;
		$this->user_skill = $user_skill;
		$this->template = $template;
		$this->objective = $objective;
		$this->reference = $reference;
		$this->template_market = $template_market;
		$this->qualification = $qualification;
	}

	public function dataTable()
	{
		return $this->user->dataTable();
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
		Objective_Rule $objective_rule,
		Reference_Rule $reference_rule,
		Qualification_Rule $qualification_rule
	) {
		\Log::info('post Profile', $request->all());
		$user = \JWTAuth::toUser($request->get('token'));

		if ($user->id != $id) {
			return response()->json(['status_code' => 403,'status' => false, 'message' => 'access for denied'], 403);
		}

		if ($request->has('user')) {
			try {
				$user_rule->validate($request->get('user'), $user->id);	
				$this->user->saveFromApi($request->get('user'), $user->id);
			} catch (ValidatorAPiException $e) {
				return response()->json(['status_code' => 422, 'status' => false, 'message' => $e->getErrors()], 422);
			}
		}

		try {
			
			if ($request->has('user_educations')) {
				try {
					if (count($request->get('user_educations')) > 1) {
						foreach ($request->get('user_educations') as $user_education_data) {
								$user_education_rule->validate($user_education_data);
						}
					} else {
						$user_education_rule->validate($request->get('user_educations')[0]);
					}

					$this->user_education->saveFromApi($request->get('user_educations'), $user->id);
				} catch (ValidatorAPiException $e) {
					return response()->json(['status_code', 422, 'status' => false, 'message' => $e->getErrors()], 422);
				}	
			}
			
			if ($request->has('user_work_histories')) {
				try {
					if (count($request->get('user_work_histories')) > 1) {
						foreach ($request->get('user_work_histories') as $user_work_history_data) {
							$user_work_history_rule->validate($user_work_history_data);
						}
					} else {
						$user_work_history_rule->validate($request->get('user_work_histories')[0]);	
					}

					$this->user_work_history->saveFromApi($request->get('user_work_histories'), $user->id);
				} catch (ValidatorAPiException $e) {
					return response()->json(['status_code', 422, 'status' => false, 'message' => $e->getErrors()], 422);
				}
			}
			
			if ($request->has('user_skills')) {
				try {
					if (count($request->get('user_skills')) > 1) {
						foreach ($request->get('user_skills') as $user_skill_data) {
							$user_skill_rule->validate($user_skill_data);
						}	
					}else {
						$user_skill_rule->validate($request->get('user_skills')[0]);
					}		

					$this->user_skill->saveFromApi($request->get('user_skills'),  $user->id);
				} catch (ValidatorAPiException $e) {
					return response()->json(['status_code', 422, 'status' => false, 'message' => $e->getErrors()], 422);
				}
			}

			if ($request->has('objectives')) {

				try {
					if (count($request->get('objectives')) > 1) {
						foreach ($request->get('objectives') as $objective) {
							$objective_rule->validate($objective);
						}
					} else {
						$objective_rule->validate($request->get('objectives')[0]);
					}

					$this->objective->saveFromApi($request->get('objectives'), $id);
				} catch (ValidatorAPiException $e) {
					return response()->json(['status_code', 422, 'status' => false, 'message' => $e->getErrors()], 422);
				}
			}

			if ($request->has('references')) {
				try {
					if (count($request->get('references')) > 1) {
						foreach ($request->get('references') as $reference) {
							$reference_rule->validate($reference);
						}
					} else {
						$reference_rule->validate($request->get('references')[0]);
					}

					$this->reference->saveFromApi($request->get('references'), $id);
				} catch(ValidatorAPiException $e) {
					return response()->json(['status_code' => 422, 'status' => false, 'message' => $e->getErrors()], 422);
				}
			}

			if ($request->has('qualifications')) {
				try {
					if (count($request->get('qualifications')) > 0) {
						foreach ($request->get('qualifications') as $qualification) {
							$qualification_rule->validate($qualification);
						}
					} else {
						$qualification_rule->validate($request->get('qualifications')[0]);
					}

					$this->qualification->saveFromApi($request->get('qualifications'), $id);
				} catch (ValidatorAPiException $e) {
					return response()->json(['status_code' => 422, 'message' => $e->getErrors()]);
				}
			}
		} catch (NotFoundFieldIdException $e) {
			return response()->json(['status_code' => 442, 'message' => 'Not found property Id']);
		}
		
		return response()->json(['status_code' => 200, 'status' => true]);
	}

	public function uploadImage(Request $request)
	{
		$user = \JWTAuth::toUser($request->get('token'));

		try {
			$this->validate($request, ['avatar' => 'image']);	
			if ($request->file('avatar')->getSize() > 10485760) {
				return response()->json([
				'status_code' => 422, 'status' => false, 'message' => 'File is wrong type or over 10Mb in size!']);
			}
		} catch (ValidationException $e) {
			return response()->json([
				'status_code' => 422, 'status' => false, 'message' => $e->getErrors()],
			422);
		}
		try {
			$avatar = $this->user->uploadImage($request->file('avatar'), $user->id);
			return $avatar != ''
				? response()->json(['status_code' => 200, 'status' => true, 'data' => $avatar])
				: response()->json(['status_code' => 500, 'status' => false, 'message' => 'Error when Upload Image']);
		} catch(UploadException $e) {
			return response()->json(['status_code' => 500, 'status' => false, 'message' => $e->getErrorMessage()]);
		}
	}

	public function removePhoto($id)
	{
		return $this->user->removePhoto($id)
			? response()->json(['status_code' => 200])
			: response()->json(['status_code' => 400]);
	}

	public function getStatus(Request $request)
	{
		$user = \JWTAuth::toUser($request->get('token'));

		return response()->json([
			'status_code' => 200,
			'data' => \Setting::get('user_status')	
		]);
	}

	public function postStatus(Request $request)
	{
		try {
			$user = \JWTAuth::toUser($request->get('token'));
			$result = $this->user->editStatus($user->id, $request->get('status'));
			
			return $result
				? response()->json(['status_code' => 200,  'data' => $result])
				: response()->json(['status_code' => 400]); 
		} catch (\Exception $e) {
			return response()->json(['status_code' => 400]);
		}
	}

	public function getAnswersForAdmin()
	{
		try {
			$answers = $this->user->answerForUser(\Auth::user()->id);
			$points = [];

			foreach ($answers as $answer) {
				$points[] = $answer->pivot->point;
			}

			$data = [
				'start' => $answers[0]->id,
				'end' => $answers[count($answers) - 1]->id,
				'points' => $points
			];
			
			return response()->json(['status' => true, 'data' => $data]);
		} catch (\Exception $e) {
			return response()->json(['status' => false, 'message' => $e->getMessage()]);
		}
	}

	public function getSection($id, $section)
	{
		\Log::info('get section user', [$id, $section]);
		return $this->user->getSectionProfile($id, $section);
	}
}
