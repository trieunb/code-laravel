<?php
namespace App\Repositories\User;

use App\Repositories\Repository;

interface UserInterface extends Repository
{
	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id  if $id == null => create else update
	 * @return mixed      
	 */
	public function saveFromApi($data, $id = null);

	/**
	 * Get profile
	 * @param  int $id 
	 * @return mixed     
	 */
	public function getProfile($id);

	/**
	 * save data Register user
	 * @param  mixed $request 
	 * @param  string $token 
	 * @return void       
	 */
	public function registerUser($request, $token);

	/**
	 * Create User get inforation to Oauth2
	 * @param  array $data  
	 * @param  string $token 
	 * @return mixed        
	 */
	public function createUserFromOAuth($data, $token);

	/**
	 * 
	 */
	public function getTemplateFromUser($id);

	/**
	 * get all template from market place
	 */
	public function getAlltemplatesFromMarketPlace($user_id);

	/**
	 * Upload avatar
	 * @param  mixed $file    
	 * @param  int $user_id 
	 * @return mixed          
	 */
	public function uploadImage($file, $user_id);
}