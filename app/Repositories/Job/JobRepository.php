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
        $response = [];
		$jobs = \DB::table('jobs')->select(['jobs.*', 'job_companies.name', 'job_companies.address', 'job_companies.website', 'job_companies.logo'])
            ->join('job_companies', 'job_companies.id', '=', 'jobs.company_id');

        if ($keyword != null && $keyword != '') {
            $jobs = $cat_id != '' && $cat_id != null 
                ? $jobs->whereRaw('MATCH (jobs.title, jobs.experience, jobs.description) AGAINST (?)', [$keyword])
                    ->whereJobCatId($cat_id)
                : $jobs->whereRaw('MATCH (jobs.title, jobs.experience, jobs.description) AGAINST (?)', [$keyword]);
            $jobs = $cat_id != '' && $cat_id != null 
                ? $jobs->orWhereRaw('MATCH (job_companies.name, job_companies.description) AGAINST (?)', [$keyword])
                    ->whereJobCatId($cat_id)
                : $jobs->orWhereRaw('MATCH (job_companies.name, job_companies.description) AGAINST (?)', [$keyword]);
        }
            
        if ($salary != null && $salary != '') {
            $jobs = $jobs->where('jobs.min_salary', '>=', $salary);
        }
        
        $jobs = $jobs->get();
   
        $ids = [];

        if (count($jobs) > 0) {
            foreach ($jobs as $key => $job) {
                $ids[] = $job->id;
                $response[$key]['id'] = $job->id;
                $response[$key]['title'] = $job->title;
                $response[$key]['country'] = $job->country;
                $response[$key]['location'] = $job->location;
                $response[$key]['experience'] = $job->experience;
                $response[$key]['description'] = $job->description;
                $response[$key]['min_salary'] = $job->min_salary;
                $response[$key]['updated_at'] = $job->updated_at;
                $response[$key]['company_name'] = $job->name;
                $response[$key]['address'] = $job->address;
                $response[$key]['website'] = $job->website;
                $response[$key]['logo'] = $job->logo;
            }
        }

        $key = count($jobs);
        $jobsSecond =  $this->model->whereHas('job_skills', function($q) use($keyword){
                return $q->where('title', '=', $keyword);
            })->whereNotIn('id', $ids)->with('job_company');
        $jobsSecond =  $cat_id != '' && $cat_id != null 
            ? $jobsSecond->JobCatId($cat_id)->get()
            : $jobsSecond->get();
   
        if (count($jobsSecond) > 0) {
            foreach ($jobsSecond as $job) {
                $key += 1;
                $response[$key]['id'] = $job->id;
                $response[$key]['title'] = $job->title;
                $response[$key]['country'] = $job->country;
                $response[$key]['location'] = $job->location;
                $response[$key]['experience'] = $job->experience;
                $response[$key]['description'] = $job->description;
                $response[$key]['min_salary'] = $job->min_salary;
                $response[$key]['updated_at'] = $job->updated_at;
                $response[$key]['company_name'] = $job->job_company->name;
                $response[$key]['address'] = $job->job_company->address;
                $response[$key]['website'] = $job->job_company->website;
                $response[$key]['logo'] = $job->job_company->logo;
            }
        }
        
        return $response;
    }

}