<?php
namespace App\Repositories\Job;

use App\Models\Job;
use App\Repositories\AbstractRepository;

class JobRepository extends AbstractRepository
{
	private $model;

	public function __construct(Job $model)
	{
		$this->model = $model;
	}
}