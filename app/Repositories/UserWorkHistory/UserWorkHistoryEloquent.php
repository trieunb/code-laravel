<?php
namespace App\Repositories\UserWorkHistory;

use App\Models\UserWorkHistory;
use App\Repositories\AbstractDefineMethodRepository;
use App\Repositories\SaveFromApiTrait;
use App\Repositories\UserWorkHistory\UserWorkHistoryInterface;

class UserWorkHistoryEloquent extends AbstractDefineMethodRepository implements UserWorkHistoryInterface
{
	use SaveFromApiTrait;
	/**
	 * UserWorkHistory
	 * @var $model
	 */
	protected $model;

	/**
	 * Fields for update data
	 * @var $field_work_save
	 */
	protected $field_work_save = ['company', 'start', 'end', 'job_title', 'job_description'];

	public function __construct(UserWorkHistory $user_work_history)
	{
		$this->model = $user_work_history;
	}

	/**
	 * Create Or Update One record
	 * @param  mixed $data    
	 * @param  int $user_id 
	 * @return mixed          
	 */
	public function saveOneRecord($data, $user_id)
	{
		$dataPrepareSave = $data[0];
		$user_work_history = $dataPrepareSave['id'] ? $this->getById($dataPrepareSave['id']) : new UserWorkHistory;

		if ($dataPrepareSave['id'] == null || $dataPrepareSave['id'] == '') $user_work_history->user_id = $user_id;

		$user_work_history->company = $dataPrepareSave['company'];
		$user_work_history->start = $dataPrepareSave['start'];
		$user_work_history->end = $dataPrepareSave['end'];
		$user_work_history->job_title = $dataPrepareSave['job_title'];
		$user_work_history->job_description = $dataPrepareSave['job_description'];

		return $user_work_history->save();
	}
}