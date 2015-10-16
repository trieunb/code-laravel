<?php
namespace App\Repositories\UserEducation;

use App\Repositories\Repository;

interface UserEducationInterface extends Repository
{
	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id   
	 * @param int $user_id
	 * @return mixed      
	 */
	public function saveFromApi($data, $user_id);

	/**
	 * Save Or Update One Record
	 * @param  mixed $data    
	 * @param  int $user_id 
	 * @return mixed          
	 */
	public function saveOneRecord($data, $user_id);
}