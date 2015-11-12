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
     * @param  mixed $request 
     * @return mixed          
     */
    public function editTemplate($id, $user_id, $request);

    /**
     * Create template basic
     * @param  int $user_id 
     * @param  string $content 
     * @return mixed          
     */
    public function createTemplateBasic($user_id, $content);

    /**
     * delete template
     * @param  int $user_id, $template_id 
     * @param  string $content 
     * @return mixed          
     */
    public function deleteTemplate($id, $temp_id);

    /**
     * Create template from market place
     * @param  array $data 
     * @return bool       
     */
    public function createTemplateFromMarket(array $data);
}