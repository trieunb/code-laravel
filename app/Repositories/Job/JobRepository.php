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
			->join('job_companies', 'job_companies.id', 'jobs.company_id')
            ->whereRaw('MATCH (jobs.title, jobs.experience, jobs.description) AGAINST (?)', [$keyword])
            ->orWhereRaw('MATCH (job_companies.name, job_companies.description) AGAINST (?)', [$keyword])
            ->where('jobs.min_salary', '>=', $salary)
            ->get();

        /*$companies = \DB::table('job_companies')->select('*')
        	->join('jobs', 'jobs.company_id', '=', 'job_companies.id')
        	->whereRaw('MATCH (name, description) AGAINST (?)', [$keyword])
        	->where('jobs.min_salary', '>=', $salary);*/
	}

}