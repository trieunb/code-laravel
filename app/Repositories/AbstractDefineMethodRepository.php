<?php
namespace App\Repositories;

use App\Repositories\AbstractRepository;

abstract class AbstractDefineMethodRepository extends AbstractRepository
{
	/**
	 * Create Or Update One record
	 * @param  mixed $data    
	 * @param  int $user_id 
	 * @return mixed          
	 */
	abstract public function saveOneRecord($data, $user_id);
}