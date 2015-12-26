<?php
namespace App\Repositories\JobCategory;

use App\Models\JobCategory;
use App\Repositories\AbstractRepository;

class JobCategoryRepository extends AbstractRepository
{
	private $model;

	public function __construct(JobCategory $model)
	{
		$this->model = $model;
	}

	public function getAll()
	{
		return create_lists($this->model->select(['id', 'name', 'parent_id'])->get()->toArray());
	}
}