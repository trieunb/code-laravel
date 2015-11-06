<?php
namespace App\Repositories\Template;

use App\Models\Template;
use App\Repositories\AbstractDefineMethodRepository;
use App\Repositories\SaveFromApiTrait;
use App\Repositories\Template\TemplateInterface;

class TemplateEloquent extends AbstractDefineMethodRepository implements TemplateInterface
{
    use SaveFromApiTrait;

	protected $model;

    /**
     * Fields for update data
     * @var $field_work_save
     */
    protected $field_work_save = ['user_id', 'cat_id', 'title', 'content',
     'price', 'image', 'type', 'status'];

	public function __construct(Template $template)
	{
		$this->model = $template;
	}
    /**
     * create 
     * @param $date
     * @return mixed
     */
    public function saveTemplate($data, $user_id)
    {
        if (count($data) == 1) {
            $this->saveOneRecord($data, $user_id);
        } 

        $ids = [];
        $dataPrepareForCreate = [];
        foreach ($data as $value) {
            if ($value['id'] != null && $value['id'] != '') {
                $ids[] = $value['id'];
            } else {
                $dataPrepareForCreate[] = $value;
            }
        }

        if (count($dataPrepareForCreate) == 1) 
            $this->saveOneRecord($dataPrepareForCreate, $user_id);
        else 
            $this->model->insertMultiRecord($dataPrepareForCreate, $user_id);
    }

    /**
     * Save Or Update One Record
     * @param  mixed $data    
     * @param  int $user_id 
     * @return mixed          
     */
    public function saveOneRecord($data, $user_id)
    {
        $dataPrepareSave = $data[0];
        $user_template = $dataPrepareSave['id'] ? $this->getById($dataPrepareSave['id']) : new UserEducation;
        if ($dataPrepareSave['id'] == null) $user_template->user_id = $user_id;

        $user_template->cat_id = $dataPrepareSave['cat_id'];
        $user_template->title = $dataPrepareSave['title'];
        $user_template->content = $dataPrepareSave['content'];
        $user_template->image = $dataPrepareSave['image'];
        $user_template->price = $dataPrepareSave['price'];
        $user_template->type = $dataPrepareSave['type'];
        $user_template->status = $dataPrepareSave['status'];

        return $user_template->save();
    }

     /**
     * Get template for user
     * @param  int $id      
     * @param  int $user_id 
     * @return mixed          
     */
    public function getDetailTemplate($id, $user_id)
    {
        return $this->model->where('user_id', '=', $user_id)->findOrFail($id);

    }

    public function getBasicTemplate($user_id)
    {
        return $this->getById($user_id);
    }

     /**
     * Create template
     * @param  int $user_id  
     * @param  mixed $request
     * @param  int $type 
     * @return mixed           
     */
    public function createTemplate($user_id, $request)
    {
        $template = new Template;
        $template->user_id = $user_id;
        $template->title = $request->get('title');
        $template->price = $request->get('price');
        $template->content = $request->get('content') != '' 
            ? preg_replace('/\t|\n+/', '', $request->get('content'))
            : '<div contenteditable="true></div>';
        $template->type = $request->get('type');

        return $template->save() ? $template : null;
    }

      /**
     * Edit template
     * @param  int $id      primary key
     * @param  int $user_id   
     * @param  string $content 
     * @return mixed          
     */
    public function editTemplate($id, $user_id, $content)
    {
        $template = $this->getDetailTemplate($id, $user_id);
        $template->content = $content;

        return $template->save() ? $template : null;
    }

    /**
     * Create template basic
     * @param  int $user_id 
     * @param  string $content 
     * @return mixed          
     */
    public function createTemplateBasic($user_id, $content)
    {
        $template = Template::where('type', '=', 1)->first();
        if ( ! $template) {
            $template = new Template();
            $template->user_id = $user_id;
            $template->title = "Basic Template";
            $template->type = 1;
        }
        $template->content = $content;
        
        return $template->save() ? $template : null;
    }

    public function deleteTemplate($id, $temp_id)
    {
        $template = $this->model->where('user_id', $id)->findOrFail($temp_id);
        return $template->delete();
        
        
    }
}