<?php
namespace App\Repositories\UserSkill;

use App\Models\UserSkill;
use App\Repositories\AbstractRepository;
use App\Repositories\UserSkill\UserSkillInterface;

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
			if ($value['id'] != null && $value['id'] != '')
				$ids[] = $value['id'];
			else $dataPrepareForCreate[] = $value;
		}

		if (count($ids) > 0) {
			$dataPrepareForUpdate = [];
			foreach ($ids as $id) {
				array_walk($data, function(&$value) use ($id, &$dataPrepareForUpdate ){
					if ($id == $value['id']) 
						$dataPrepareForUpdate[] = $value;
				});
			}

			if (count($dataPrepareForUpdate) == 1) 
				$this->saveOneRecord($dataPrepareForUpdate, $user_skill);
			else 
				$this->model->updateMultiRecord($dataPrepareForUpdate, $ids);
		}

		if (count($dataPrepareForCreate) == 1) 
			$this->saveOneRecord($dataPrepareForCreate, $user_id);
		else 
			$this->model->insertMultiRecord($dataPrepareForCreate, $user_id);
	}

	public function saveOneRecord($data, $user_id)
	{
		$dataPrepareSave = $data[0];
		$user_skill = $dataPrepareSave['id'] ? $this->getById($dataPrepareSave['id']) : new UserSkill;

		if ( $dataPrepareSave['id'] == null) $user_skill->user_id = $user_id;

		$user_skill->skill_name = $dataPrepareSave['skill_name'];
		$user_skill->skill_test = $dataPrepareSave['skill_test'];
		$user_skill->skill_test_point = $dataPrepareSave['skill_test_point'];
		$user_skill->experience = $dataPrepareSave['experience'];

		return $user_skill->save();
	}
}