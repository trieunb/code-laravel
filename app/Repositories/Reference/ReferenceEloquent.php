<?php
namespace App\Repositories\Reference;

use App\Models\Reference;
use App\Repositories\AbstractDefineMethodRepository;
use App\Repositories\Reference\ReferenceInterface;
use App\Repositories\SaveFromApiTrait;

class ReferenceEloquent extends AbstractDefineMethodRepository implements ReferenceInterface
{
	use SaveFromApiTrait;
	/**
	 * Reference
	 * @var $model
	 */
	protected $model;

	/**
	 * Fields for update data
	 * @var $field_work_save
	 */
	protected $field_work_save = ['reference', 'content'];

	public function __construct(Reference $model)
	{
		$this->model = $model;
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
		$reference = $dataPrepareSave['id'] ? $this->getById($dataPrepareSave['id']) : new Reference;

		if($dataPrepareSave['id'] == null) $reference->user_id = $user_id;

		$reference->reference = $dataPrepareSave['reference'];
		$reference->content = $dataPrepareSave['content'];

		return $reference->save();
	}


}