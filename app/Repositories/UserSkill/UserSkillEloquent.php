<?php
namespace App\Repositories\UserSKill;

use App\Models\UserSkill;
use App\Repositories\AbstractRepository;
use App\Repositories\UserSKill\UserSkillInterface;

class UserSkillEloquent extends AbstractRepository implements UserSkillInterface
{
	protected $model;

	public function __construct(App\Models\UserSkill $user_skill)
	{
		$this->model = $user_skill;
	}

	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id   
	 * @return mixed      
	 */
	public function save($request, $id = null)
	{
		$user_skill = $id ? $this->getById($id) : new UserSkill;

		if ( !$id) $user_skill->user_id = \Auth::user()->id;

		$user_skill->skill_name = $request->get('skill_name');
		$user_skill->skill_test = $request->get('skill_test');
		$user_skill->skill_test_point = $request->get('skill_test_point');
		$user_skill->exprience = $request->get('exprience');

		return $user_skill->save();
	}
}