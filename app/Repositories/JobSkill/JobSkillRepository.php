<?php
namespace App\Repositories\JobSkill;

use App\Repositories\AbstractRepository;
use App\Models\JobSkill;

class JobSkillRepository extends AbstractRepository
{
	protected $model;

	public function __construct(JobSkill $model)
	{
		$this->model = $model;
	}

	public function getAll($fileds = ['*'])
	{
		return $this->model->select($fileds)->get();
	}
}