<?php
namespace App\Repositories\Question;

use App\Models\Question;
use App\Repositories\AbstractRepository;
use App\Repositories\Question\QuestionInterface;

class QuestionEloquent extends AbstractRepository implements QuestionInterface
{
	/**
     * Question
     * @var $model
     */
    protected $model;

	public function __construct(Question $model)
	{
		$this->model = $model;
	}

	/**
	 * Get data with DataTable
	 * @return mixed 
	 */
	public function dataTable()
	{
		return \Datatables::of($this->model->select('*'))
            ->addColumn('action', function ($question) {
            	return '<div class="btn-group" role="group" aria-label="...">
            		<a class="btn btn-success" href="'.route('admin.question.get.answer', $question->id).'">Answer Of User</a>
                  	<a class="btn btn-primary edit" href="' .route('admin.question.get.edit', $question->id) . '" data-toggle="modal" data-target="#modal-admin"><i class="glyphicon glyphicon-edit"></i></a>
                  	<a class="delete-data btn btn-danger" data-src="' . route('api.question.get.deleteAdmin', $question->id) . '"><i class="glyphicon glyphicon-remove"></i></a>
                  
                </div>';
            })
            ->make(true);
	}

	/**
	 * Edit question from Admin Area
	 * @param  mixed $request 
	 * @return bool          
	 */
	public function saveFromAdminArea($request)
	{
		$question = $request->has('id') && $request->get('id') != ''
			? $this->getById($request->get('id'))
			: new Question;
		$question->content = $request->get('content');
		$question->publish = $request->has('publish') ? 1 : 0;

		return $question->save();
	}

	
}