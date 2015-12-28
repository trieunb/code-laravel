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
                  	<a class="btn btn-primary edit" href="' .route('admin.question.get.edit', $question->id) . '" ><i class="glyphicon glyphicon-edit"></i></a>
                  	<a class="delete-data btn btn-danger" data-src="' . route('api.question.get.deleteAdmin', $question->id) . '"><i class="glyphicon glyphicon-remove"></i></a>
                </div>';
            })
            ->editColumn('content', function($user) {
                return wordwrap(str_limit($user->content, $limit = 150, $end = '...'), 50, "<br />\n", true);
            })
            ->addColumn('publish', function($question) {
                return ($question->publish)
                    ? '<span>Yes</span>'
                    : '<span>No</span>';
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

    public function getQuestions()
    {
        return $this->getDataWhereClause('publish', '=', 1);
    }
}