<?php
namespace App\Repositories\UserSKill;

use App\Models\UserSkill;
use App\Repositories\AbstractRepository;
use App\Repositories\UserSKill\UserSkillInterface;

class UserSkillEloquent extends AbstractRepository implements UserSkillInterface
{
	protected $model;

	public function __construct(UserSkill $user_skill)
	{
		$this->model = $user_skill;
	}

	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param int $user_id
	 * @return mixed      
	 */
	public function saveFromApi($data, $user_id)
	{
		if (count($data) == 1) {
			$this->saveOneRecord($data, $user_id);
		}
		
		$ids = [];
		$dataPrepareForCreate = [];

		foreach ($data as $value) {
			if ($value['id'] != null)
				$ids[] = $value['id'];
			else $dataPrepareForCreate[] = $value;
		}

		$dataIds_has_ids = $this->getDataWhereIn('id', $ids);
		if (count($dataIds_has_ids) > 0) {
			$dataPrepareForUpdate = [];
			foreach ($dataIds_has_ids as $user_skill) {
				array_walk($data, function(&$value) use ($user_skill, &$dataPrepareForUpdate ){
					if ($user_skill->id == $value['id']) 
						$dataPrepareForUpdate[] = $value;
				});
			}

			if (count($dataPrepareForUpdate) == 1) 
				$this->saveOneRecord($dataPrepareForUpdate, $user_skill);
			else 
				$this->model->updateMultiRecord($dataPrepareForUpdate);
		}

		if (count($dataPrepareForCreate) == 1) 
			$this->saveOneRecord($dataPrepareForCreate, $user_id);
		else 
			$this->model->insertMultiRecord($dataPrepareForCreate, $user_id);
	}

	public function saveOneRecord($data, $user_id)
	{
		$user_skill = $data['id'] ? $this->getById($data['id']) : new UserSkill;

		if ( $data['id'] == null) $user_skill->user_id = $user_id;

		$user_skill->skill_name = $data['skill_name'];
		$user_skill->skill_test = $data['skill_test'];
		$user_skill->skill_test_point = $data['skill_test_point'];
		$user_skill->experience = $data['experience'];

		return $user_skill->save();
	}
}