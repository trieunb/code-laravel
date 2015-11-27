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

	public function index()
	{
		return Datatables::of($this->model->select('*'))
            ->addColumn('action', function ($question) {
                return '<a href="'.route('admin.question.get.edit', $question->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
            })
            ->addColumn('action', function ($question) {
                return '<a href="'.route('admin.question.get.delete', $question->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
            })
            ->make(true);
	}
}