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
    protected $field_work_save = ['user_id', 'title', 'source', 'source_convert', 'template'];

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

        $user_template->title = $dataPrepareSave['title'];
        $user_template->source = $dataPrepareSave['source'];
        $user_template->source_convert = $dataPrepareSave['source_convert'];
        $user_template->template = $dataPrepareSave['template'];

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
        $template->template_full = $request->get('template_full');
        $template->type = $request->get('type');

        return $template->save();
    }

      /**
     * Edit template
     * @param  int $id      primary key
     * @param  string $content 
     * @return mixed          
     */
    public function editTemplate($id, $content)
    {
        $template = $this->getById($id);
        $template->template_full = $content;

        return $template->save();
    }
}