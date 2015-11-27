<?php
namespace App\Repositories\Qualification;

use App\Models\Qualification;
use App\Repositories\AbstractDefineMethodRepository;
use App\Repositories\SaveFromApiTrait;
use App\Repositories\Qualification\QualificationInterface;

class QualificationEloquent extends AbstractDefineMethodRepository implements QualificationInterface
{
	use SaveFromApiTrait;
	/**
	 * Quanlification
	 * @var $model
	 */
	protected $model;

	/**
	 * Fields for update data
	 * @var $field_work_save
	 */
	protected $field_work_save = ['content', 'position'];

	public function __construct(Qualification $qualification)
	{
		$this->model = $qualification;
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
		$objective = $dataPrepareSave['id'] ? $this->getById($dataPrepareSave['id']) : new Qualification;

		if($dataPrepareSave['id'] == null) $objective->user_id = $user_id;

		$objective->content = $dataPrepareSave['content'];
		$objective->position = $dataPrepareSave['position'];

		return $objective->save();
	}
}