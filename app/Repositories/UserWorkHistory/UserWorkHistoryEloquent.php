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
	 * @param int $user_id
	 * @return mixed      
	 */
	public function saveFromApi($data, $user_id)
	{
		if (count($data) == 1) {
			$this->saveOneRecord($data, $user_id);
		}
		
		$ids = [];
		$dataPrepareForCreate = [];

		foreach ($data as $value) {
			if ($value['id'] != null && $value['id'] != '') {
				$ids[] = $value['id'];
			} else {
				$dataPrepareForCreate[] = $value;
			}
		}

		if (count($ids) > 0) {
			$dataPrepareForUpdate = [];
			foreach ($ids as $id) {
				array_walk($data, function(&$value) use ($id, &$dataPrepareForUpdate){
					if ($id == $value['id'])
						$dataPrepareForUpdate[] = $value;
				});
			}

			if (count($dataPrepareForUpdate) == 1) $this->saveOneRecord($dataPrepareForUpdate, $user_id);
			else $this->model->updateMultiRecord($dataPrepareForUpdate, $ids);
		}

		if (count($dataPrepareForCreate) == 1) 
			$this->saveOneRecord($dataPrepareForCreate, $user_id);
		else 
			$this->model->insertMultiRecord($dataPrepareForCreate, $user_id);
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
		$user_work_history = $dataPrepareSave['$id'] ? $dataPrepareSave['$id'] : new UserWorkHistory;

		if ($dataPrepareSave['$id'] == null) $user_work_history->user_id = $user_id;

		$user_work_history->company = $dataPrepareSave['company'];
		$user_work_history->sub_title = $dataPrepareSave['sub_title'];
		$user_work_history->start = $dataPrepareSave['start'];
		$user_work_history->end = $dataPrepareSave['end'];
		$user_work_history->job_title = $dataPrepareSave['job_title'];
		$user_work_history->job_description = $dataPrepareSave['job_description'];

		return $user_work_history->save();
	}
}