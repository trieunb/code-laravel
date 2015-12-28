<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\JobCategory\JobCategoryRepository;
use App\Repositories\JobSkill\JobSkillRepository;
use App\Repositories\Job\JobRepository;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, JobRepository $job)
    {
        try {
            $filters = [
                'keyword' => $request->get('keyword'),
                'country' => $request->get('country'),
                'salary'  => $request->get('salary'),
                'cat_id'  => $request->get('cat_id'),
                'page'    => is_null($request->get('page')) ? 1 : $request->get('page')
            ];
            return response()->json(['status_code' => 200, 'data' => $job->seachJob($filters)]);
        } catch (Exception $e) {
            return response()->json(['status_code' => 400, 'message' => 'Data not found!']);
        }
    }

    public function getListJobCategory(JobCategoryRepository $job_category)
    {
        return $job_category->getAll() != null
            ? response()->json(['status_code' => 200, 'data' => $job_category->getAll()], 200, [], JSON_NUMERIC_CHECK)
            : response()->json(['status_code' => 400, 'message' => 'Data not found!']);
    }

    public function getListJobSkill(JobSkillRepository $job_skill)
    {
        return count($job_skill->getAll()) != 0
            ? response()->json(['status_code' => 200, 'data' => $job_skill->getAll()])
            : response()->jon(['status_code' => 400, 'message' => 'Data not found!']);
    }
}
