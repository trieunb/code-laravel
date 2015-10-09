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
	 * @param  int $id   
	 * @param int $user_id
	 * @return mixed      
	 */
	public function save($data, $id = null, $user_id)
	{
		$user_skill = $id ? $this->getById($id) : new UserSkill;

		if ( $id == null) $user_skill->user_id = $user_id;

		$user_skill->skill_name = $data['skill_name'];
		$user_skill->skill_test = $data['skill_test'];
		$user_skill->skill_test_point = $data['skill_test_point'];
		$user_skill->experience = $data['experience'];

		return $user_skill->save();
	}
}