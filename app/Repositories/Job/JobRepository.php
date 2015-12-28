<?php
namespace App\Repositories\Job;

use App\Models\Job;
use App\Models\JobCategory;
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

    public function seachJob(array $filters)
    {
        $jobs = \DB::table('jobs')->distinct()
            ->select([
                'jobs.*', 'job_companies.name','job_companies.address',
                'job_companies.website', 'job_companies.logo'
            ])
            ->join('job_companies', 'job_companies.id', '=', 'jobs.company_id')
            ->leftJoin('job_skill_pivot', 'job_skill_pivot.job_id', '=', 'jobs.id')
            ->leftJoin('job_skills', 'job_skills.id', '=', 'job_skill_pivot.job_skill_id');

        if ($filters['country'])
            $jobs = $jobs->where('jobs.country', '=', $filters['country']);

        if ($filters['salary']) {
            $jobs = $jobs->where('jobs.min_salary', '>=', $filters['salary']);
        }

        if ($filters['cat_id']) {

            $childrenCategory = JobCategory::where('parent_id', '=', $filters['cat_id'])->get();
            if (count($childrenCategory) > 0) {
                $childrenIds = [];
                foreach ($childrenCategory as $children) {
                    $childrenIds[] = $children->id;
                }

                $jobs = $jobs->whereIn('job_cat_id', $childrenIds);

            } else {
                $jobs = $jobs->whereJobCatId($filters['cat_id']);
            }

        }
        if ($filters['keyword']) {
            $jobs = $jobs->whereRaw('(jobs.title LIKE ? 
                OR job_companies.name LIKE ?
                OR job_skills.title LIKE ?)',
                ['%'.$filters['keyword'].'%', '%'.$filters['keyword'].'%', '%'.$filters['keyword'].'%']
            );
        }

        $offset = ($filters['page'] - 1) * config('paginate.limit');
        $tmpJobs = $jobs;
        $count = ceil(count($jobs->get()) / config('paginate.limit'));

        $jobs = $tmpJobs->skip($offset)
            ->take(config('paginate.limit'))
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($jobs as $job) {
            $job->id = (int)$job->id;
            $job->job_cat_id = (int)$job->job_cat_id;
            $job->company_id = (int)$job->company_id;
            $job->min_salary = (double)$job->min_salary;
        }

        return ['jobs' => $jobs, 'totalPage' => $count, 'currentPage' => $filters['page']];
    }

}