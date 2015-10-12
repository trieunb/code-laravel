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
			if ($value != null) {
				$ids[] = $value['id'];
			} else {
				$dataPrepareForCreate[] = $value;
			}
		}
		
		$dataIds_has_ids = $this->getDataWhereIn('id', $ids);

		if (count($dataIds_has_ids) > 0) {
			$dataPrepareForUpdate = [];
			foreach ($dataIds_has_ids as $user_work_history) {
				array_walk($data, function(&$value) use ($user_work_history, &$dataPrepareForUpdate){
					if ($user_work_history->id == $value['id'])
						$dataPrepareForUpdate[] = $value;
				});
			}

			if (count($dataPrepareForUpdate) == 1) $this->saveOneRecord($dataPrepareForUpdate, $user_id);
			else $this->model->updateMultiRecord($dataPrepareForUpdate);
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
		$user_work_history = $data['$id'] ? $data['$id'] : new UserWorkHistory;

		if ($data['$id'] == null) $user_work_history->user_id = $user_id;

		$user_work_history->company = $data['company'];
		$user_work_history->start = $data['start'];
		$user_work_history->end = $data['end'];
		$user_work_history->job_title = $data['job_title'];
		$user_work_history->job_description = $data['job_description'];

		return $user_work_history->save();
	}
}