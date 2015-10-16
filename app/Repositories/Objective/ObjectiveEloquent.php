<?php
namespace App\Repositories\Objective;

use App\Models\Objective;
use App\Repositories\AbstractDefineMethodRepository;
use App\Repositories\SaveFromApiTrait;
use App\Repositories\Objective\ObjectiveInterface;

class ObjectiveEloquent extends AbstractDefineMethodRepository implements ObjectiveInterface
{
	use SaveFromApiTrait;

	/**
	 * Objective
	 * @var $model
	 */
	protected $model;

	/**
	 * Fields for update data
	 * @var $field_work_save
	 */
	protected $field_work_save = ['title', 'content'];

	public function __construct(Objective $objective)
	{
		$this->model = $objective;
	}

	/**
	 * Create Or Update One Record
	 * @param  mixed $data    
	 * @param  int $user_id 
	 * @return mixed          
	 */
	public function saveOneRecord($data, $user_id)
	{
		$dataPrepareSave = $data[0];
		$objective = $dataPrepareSave['id'] ? $this->getById($dataPrepareSave['id']) : new Objective;

		if($dataPrepareSave['id'] == null) $objective->user_id = $user_id;

		$objective->title = $dataPrepareSave['title'];
		$objective->content = $dataPrepareSave['content'];

		return $objective->save();
	}
}