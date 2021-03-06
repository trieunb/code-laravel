<?php
namespace App\Repositories\UserSkill;

use App\Models\UserSkill;
use App\Models\JobSkill;
use App\Models\User;
use App\Repositories\SaveFromApiTrait;
use App\Repositories\AbstractDefineMethodRepository;
use App\Repositories\UserSkill\UserSkillInterface;
use App\Repositories\AbstractRepository;

class UserSkillEloquent extends AbstractRepository implements UserSkillInterface
{
	use SaveFromApiTrait;
	
	/**
	 * UserSKill
	 * @var $model
	 */
	protected $model;

	/**
	 * Fields for update data
	 * @var $field_work_save
	 */
	protected $field_work_save = [
		'skill_name', 'skill_test', 'skill_test_point', 
		'experience', 'position'
	];

	public function __construct(UserSkill $user_skill)
	{
		$this->model = $user_skill;
	}

	public function saveAndUpdateSkill($data, $user_id)
	{
		$user = User::find($user_id);
		$user->skills()->sync(array_flatten($data));
	}

	/**
	 * Create Or Update One record
	 * @param  mixed $data    
	 * @param  int $user_id 
	 * @return mixed          
	 */
	public function saveOneRecord($data, $user_id)
	{
		$dataPrepareSave = $data[0];
		$user_skill = $dataPrepareSave['id'] ? $this->getById($dataPrepareSave['id']) : new UserSkill;

		if ( $dataPrepareSave['id'] == null) $user_skill->user_id = $user_id;

		$user_skill->skill_name = $dataPrepareSave['skill_name'];
		$user_skill->skill_test = $dataPrepareSave['skill_test'];
		$user_skill->skill_test_point = $dataPrepareSave['skill_test_point'];
		$user_skill->experience = $dataPrepareSave['experience'];
		$user_skill->position = $dataPrepareSave['position'];

		return $user_skill->save();
	}
}