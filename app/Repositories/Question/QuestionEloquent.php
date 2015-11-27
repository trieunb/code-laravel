<?php
namespace App\Repositories\Question;

class QuestionEloquent
{
	public function index()
	{
		$questions = [];
		
		foreach (\Setting::get('questions') as $question) {
			$questions[] = $question['question'];
		}

		return $questions;
	}
}