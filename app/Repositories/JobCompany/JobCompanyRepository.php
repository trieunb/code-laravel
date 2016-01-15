<?php
namespace App\Repositories\JobCompany;

use App\Repositories\AbstractRepository;
use App\Models\Job;
use App\Models\JobCompany;

class JobCompanyRepository extends AbstractRepository
{
    protected $model;

    public function __construct(JobCompany $model)
    {
        $this->model = $model;
    }
}