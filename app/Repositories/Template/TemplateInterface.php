<?php
namespace App\Repositories\Template;

use App\Repositories\Repository;

interface TemplateInterface extends Repository
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
     * Get template for user
     * @param  int $id      
     * @param  int $user_id 
     * @return mixed          
     */
    public function getDetailTemplate($id, $user_id);

    public function getBasicTemplate($user_id);

    /**
     * Create template
     * @param  int $user_id  
     * @param  mixed $request
     * @param  int $type 
     * @return mixed           
     */
    public function createTemplate($user_id, $request);

    /**
     * Edit template
     * @param  int $id      primary key
     * @param  int $user_id    
     * @param  string $content 
     * @return mixed          
     */
    public function editTemplate($id, $user_id, $content);

    /**
     * Create template basic
     * @param  int $user_id 
     * @param  string $content 
     * @return mixed          
     */
    public function createTemplateBasic($user_id, $content);
}