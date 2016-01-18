<?php
namespace App\Repositories\Job;

use App\Models\Job;
use App\Models\User;
use App\Models\JobCategory;
use App\Repositories\AbstractRepository;
use App\Repositories\UserInterface;
use Baum\Extensions\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use yajra\Datatables\keyword;

class JobRepository extends AbstractRepository
{
    protected $model;
    protected $user;

    public function __construct(Job $model, User $user)
    {
        $this->model = $model;
        $this->user = $user;
    }

    public function search(array $filters)
    {
        // Build query
        $jobsQuery = $this->model
            ->select('jobs.*')
            ->distinct()
            ->with('company', 'category')
            ->join('job_companies', 'job_companies.id', '=', 'jobs.company_id')
            ->leftJoin('job_skill_pivot', 'job_skill_pivot.job_id', '=', 'jobs.id')
            ->leftJoin('job_skills', 'job_skills.id', '=', 'job_skill_pivot.job_skill_id');

        if (isset($filters['country']) && $filters['country']) {
            $jobsQuery->where('jobs.country', $filters['country']);
        }
        if (isset($filters['salary']) && $filters['salary']) {
            $jobsQuery->where('jobs.min_salary', '>=', $filters['salary']);
        }

        if (isset($filters['cat_id']) && $filters['cat_id']) {
            $childCatIds = JobCategory::where('parent_id', $filters['cat_id'])
                ->get()->pluck('id');
            if (count($childCatIds)) {
                $childCatIds->prepend($filters['cat_id']);
                $jobsQuery->whereIn('job_cat_id', $childCatIds);
            } else {
                $jobsQuery->whereJobCatId($filters['cat_id']);
            }
        }
        if (isset($filters['keyword']) && $filters['keyword']) {
            $jobsQuery = $jobsQuery->whereRaw('(jobs.title LIKE ?
                OR job_companies.name LIKE ?
                OR job_skills.title LIKE ?)',
                array_fill(0, 3, '%'.$filters['keyword'].'%')
            );
        }

        // Paginate
        $page = (int) $filters['page'];
        $offset = ($page - 1) * config('paginate.limit');
        $total = $jobsQuery->count('jobs.id');

        $jobs = $jobsQuery
            ->skip($offset)
            ->orderBy('jobs.updated_at', 'DESC')
            ->take(config('paginate.limit'))
            ->get();

        return [
            'jobs'     => $jobs,
            'total'    => $total,
            'per_page' => config('paginate.limit')
        ];
    }

    /**
     * List job matching for user
     */
    public function createListJobMatching($user_id, $job_ids)
    {
        $user = $this->user->FindOrFail($user_id);
        $user->jobs_matching()->detach($job_ids);
        return $user->jobs_matching()->attach($job_ids);
    }

    /**
     * read job matching
     */
    public function markJobMatchAsRead($user_id, $job_ids)
    {
        $user = $this->user->FindOrFail($user_id);
        $user->jobs_matching()->detach($job_ids);
        return $user->jobs_matching()->attach($job_ids, ['read' => 1]);
    }

    /**
     * delete job matching
     */
    public function deleteJobMatching($job_id)
    {
        $job = $this->getById($job_id);
        foreach ($job->user_jobs_matching as $user) {
            $ids[] = $user['id'];
        }
        return $job->user_jobs_matching()->detach($ids);
    }
}
