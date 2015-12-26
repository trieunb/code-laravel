<?php
namespace App\Repositories\Job;

use App\Models\Job;
use App\Repositories\AbstractRepository;
use Baum\Extensions\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use yajra\Datatables\keyword;

class JobRepository extends AbstractRepository
{
	private $model;

	public function __construct(Job $model)
	{
		$this->model = $model;
	}

	public function seachJob($keyword, $countryCode, $salary, $cat_id, $currentPage = null)
	{
        $response = [];
        $ids = [];

		$jobs = \DB::table('jobs')->select(['jobs.*', 'job_companies.name', 'job_companies.address', 'job_companies.website', 'job_companies.logo'])
            ->join('job_companies', 'job_companies.id', '=', 'jobs.company_id')
            ->where('jobs.country', '=', 'VN');

        if ($salary != null && $salary != '') {
            $jobs = $jobs->where('jobs.min_salary', '>=', $salary);
        }

        if ($cat_id != '' && $cat_id != null) {
            $jobs = $jobs->whereJobCatId($cat_id);
        }
        if ($keyword != null && $keyword != '') {
            $jobs = $jobs->whereRaw('(MATCH (jobs.title, jobs.experience, jobs.description) AGAINST (?)', [$keyword]);
            $jobs = $jobs->orWhereRaw('MATCH (job_companies.name, job_companies.description) AGAINST (?))', [$keyword]);
        }
        
        $jobs = $jobs->get();
        
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
                $response[$key]['updated_at'] = $job->updated_at->toDateTimeString();
                $response[$key]['company_name'] = $job->job_company->name;
                $response[$key]['address'] = $job->job_company->address;
                $response[$key]['website'] = $job->job_company->website;
                $response[$key]['logo'] = $job->job_company->logo;
            }
        }
        $currentPage = is_null($currentPage) ? 1 : $currentPage;

        $response = Collection::make($response)->sortByDesc('updated_at')->toArray();
        $offset = ($currentPage * config('paginate.limit')) - config('paginate.limit');
        $itemsForCurrentPage = array_slice($response, $offset, config('paginate.limit'), true);

        $pagination = new LengthAwarePaginator($itemsForCurrentPage, count($response), config('paginate.limit'), $currentPage);

        $result = $pagination->toArray(); 
        $result['data'] = array_values($result['data']);

        return $result;
    }

}