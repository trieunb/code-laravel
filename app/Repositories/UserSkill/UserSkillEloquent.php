<?php
namespace App\Repositories\UserSkill;

use App\Models\UserSkill;
use App\Models\JobSkill;
use App\Models\User;
use App\Repositories\SaveFromApiTrait;
use App\Repositories\AbstractDefineMethodRepository;
use App\Repositories\UserSkill\UserSkillInterface;
use App\Repositories\AbstractRepository;
use App\Repositories\JobSkill\JobSkillRepository;

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
		'name', 'level', 'position'
	];

	public function __construct(UserSkill $user_skill)
	{
		$this->model = $user_skill;
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

		$user_skill->name = $dataPrepareSave['name'];
		$user_skill->level = $dataPrepareSave['level'];
		$user_skill->position = $dataPrepareSave['position'];

		return $user_skill->save();
	}
}