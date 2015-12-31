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

    public function getAll($fields = ['id', 'name' , 'parent_id'])
    {
        $job_categories = create_lists($this->model->get($fields)->toArray());

        return sort_lists($job_categories);
    }
}