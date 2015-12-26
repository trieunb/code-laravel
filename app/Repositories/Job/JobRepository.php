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

		$jobs = \DB::table('jobs')->distinct()->select(['jobs.*',  'job_companies.name', 'job_companies.address', 'job_companies.website', 'job_companies.logo'])
            ->join('job_companies', 'job_companies.id', '=', 'jobs.company_id')
            ->leftJoin('job_skill_pivot', 'job_skill_pivot.job_id', '=', 'jobs.id')
            ->leftJoin('job_skills', 'job_skills.id', '=', 'job_skill_pivot.job_skill_id')
            ->where('jobs.country', '=', $countryCode);
        
        if ($salary != null && $salary != '') {
            $jobs = $jobs->where('jobs.min_salary', '>=', $salary);
        }
        
        if ($cat_id != '' && $cat_id != null) {
            $jobs = $jobs->whereJobCatId($cat_id);
        }
        if ($keyword != null && $keyword != '') {
            $jobs = $jobs->whereRaw('(jobs.title LIKE ? 
                OR job_companies.name LIKE ?
                OR job_skills.title LIKE ?)',
                ['%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%']
             );
        }

        $offset = ($currentPage - 1) * config('paginate.limit');
        $tmpJobs = $jobs;
        $count = count($jobs->get());

        $jobs = $tmpJobs->skip($offset)
            ->take(config('paginate.limit'))
            ->orderBy('updated_at', 'desc')
            ->get();

        return ['jobs' => $jobs, 'total' => $count, 'currentPage' => is_null($currentPage) ? 1 : $currentPage];
    }

}