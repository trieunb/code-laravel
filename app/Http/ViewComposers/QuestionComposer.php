<?php
namespace App\Http\ViewComposers;

use App\Repositories\Question\QuestionInterface;
use Illuminate\Contracts\View\View;

class QuestionComposer 
{
	private $question;

	public function __construct(QuestionInterface $question)
	{
		$this->question = $question;
	}

	public function compose(View $view)
	{
		$view->with('list_questions', $this->question->lists('content', 'id'));
	}
}