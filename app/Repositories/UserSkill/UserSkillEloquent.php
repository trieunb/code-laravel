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
}