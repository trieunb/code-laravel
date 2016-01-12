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
	 * Get template for user id
	 * @param  int $id
	 * @return mixed
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

	/**
	 * Edit Status
	 * @param  int $id     
	 * @param  int $status 
	 * @return bool         
	 */
	public function editStatus($id, $status);

	/**
	 * Remove photo
	 * @param  int $id 
	 * @return bool     
	 */
	public function removePhoto($id);

	/**
	 * update User get inforation to facebook
	 * @param  array $data  
	 * @param  string $token 
	 * @return mixed        
	 */
	public function createOrUpdateUserFacebook($data, $token, $id);

	/**
	 * Get datatable of user
	 * @return mixed 
	 */
	public function dataTable();

	public function updateUserLogin($user, $token);

	/**
	 * Report user by month
	 */
	public function reportUserMonth();

	/**
	 * Report user by gender
	 */
	public function reportUserGender();

	/**
	 * Report user by region
	 */
	public function reportUserRegion();

}