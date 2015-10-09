<?php
namespace App\Repositories\UserEducation;

use App\Repositories\Repository;

interface UserEducationInterface extends Repository
{
	/**
	 * get data by user id
	 * @param  int $user_id 
	 * @return mixed          
	 */
	public function getByUserIdAfterAuthenticate($user_id);
}