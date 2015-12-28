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

	public function getAll()
	{
		return $this->model->select(['id', 'title'])->get();
	}
}