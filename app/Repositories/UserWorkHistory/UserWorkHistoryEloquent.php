<?php
namespace App\Repositories\UserWorkHistory;

use App\Models\UserWorkHistory;
use App\Repositories\AbstractRepository;
use App\Repositories\UserWorkHistory\UserWorkHistoryInterface;

class UserWorkHistoryEloquent extends AbstractRepository implements UserWorkHistoryInterface
{
	protected $model;

	public function __construct(UserWorkHistory $user_work_history)
	{
		$this->model = $user_work_history;
	}

	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id   
	 * @return bool      
	 */
	public function save($request, $id = null)
	{
		$user_work_history = $id ? $this->getById($id) : new UserWorkHistory;

		if ( !$id) $user_work_history->user_id = \Auth::user()->id;

		$user_work_history->company = $request->get('company');
		$user_work_history->start = $request->get('start');
		$user_work_history->end = $request->get('end');
		$user_work_history->job_title = $request->get('job_title');
		$user_work_history->job_description = $request->get('job_description');

		return $user_work_history->save();
	}
}