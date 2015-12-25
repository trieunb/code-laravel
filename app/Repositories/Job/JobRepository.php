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

	public function seachJob($keyword, $countryCode, $salary, $cat_id)
	{
		$jobs = \DB::table('jobs')->select('*')
            ->whereRaw('MATCH (jobs.title, jobs.experience, jobs.description) AGAINST (?)', [$keyword])
            ->where('jobs.min_salary', '>=', $salary)
            ->get();

        $companies = \DB::table('job_companies')->select('*')
        	->join('jobs', 'jobs.company_id', '=', 'job_companies.id')
        	->whereRaw('MATCH (name, description) AGAINST (?)', [$keyword])
        	->where('jobs.min_salary', '>=', $salary);
	}

}