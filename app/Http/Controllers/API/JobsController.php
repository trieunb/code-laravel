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
    public function index(JobRepository $job)
    {
        // dd($job->seachJob('Victor', ''))
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
