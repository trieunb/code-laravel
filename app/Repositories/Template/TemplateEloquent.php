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
    protected $field_work_save = ['user_id', 'title', 'content',
    'image', 'type'];

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
        $dataPrepareSave = isset($data[0]) ? isset($data) : $data;
        $template = isset($dataPrepareSave['id']) ? $this->getById($dataPrepareSave['id']) : new Template;
        if (!isset($dataPrepareSave['id']) || $dataPrepareSave['id'] == null) $template->user_id = $user_id;

        $template->title = $dataPrepareSave['title'];
        $template->content = $dataPrepareSave['content'];
        $template->image = $dataPrepareSave['image'];
        $template->type = $dataPrepareSave['type'];
        $template->source_file_pdf = $dataPrepareSave['source_file_pdf'];
        Template::makeSlug($template, false);

        return $template->save();
    }

    /**
     * Get template for user
     * @param  int $id      
     * @param  int $user_id 
     * @return mixed          
     */
    public function forUser($id, $user_id)
    {
        return $this->model->whereUserId($user_id)->findOrFail($id);

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
        $template->content = $request->get('content') != '' 
            ? preg_replace('/\t|\n+/', '', $request->get('content'))
            : '<div contenteditable="true></div>';
        $template->type = $request->get('type');
        Template::makeSlug($template, false);
        
        return $template->save() ? $template : null;
    }

    /**
     * Edit template
     * @param  int $id      primary key
     * @param  int $user_id   
     * @param  string $section   
     * @param  mixed $request 
     * @return mixed          
     */
    public function editTemplate($id, $user_id, $section, $request)
    {
        $template = $this->forUser($id, $user_id);
        $sec = $template->section;
        $tmp = '';
        if ($section == 'availability') {

            foreach (\Setting::get('user_status') as $status) {
                if ($status['id'] == $request->get('content'))
                    $tmp = '<div class="availability"><p style="font-weight:600">'.$status['value'].'</p></div>';
            }

            $user = \App\Models\User::find($user_id);
            $user->status = $request->get('content');

            if ($user->save()) {
                $data = editSection($section, $tmp, $template->content);
            }

            return null;
        } else {
            $data = editSection($section, $request->get('content'), $template->content);
        }

        $template->content = $data['content'];
        $template->section = array_set($sec, $section, $data['section']);

        return $template->save() ? $template : null;
    }

    /**
     * Create template basic
     * @param  int $user_id 
     * @param  string $content 
     * @return mixed          
     */
    public function createTemplateBasic($user_id, $section, $content)
    {
        $template = $this->model->whereUserId($user_id)
            ->whereType(2)
            ->first();

        if ( ! $template) {
            $template = new Template();
            $template->user_id = $user_id;
            $template->title = "Basic Template";
            $template->type = 2;
            Template::makeSlug($template);
        }

        $template->content = $content;
        $template->section = $section;
        
        return $template->save() ? $template : null;
    }

    /**
     * Delete template
     * @param  int $id
     * @param  int $user_id
     * @param  string $content 
     * @return mixed          
     */
    public function deleteTemplate($id, $user_id)
    {
        $template = $this->forUser($id, $user_id);
       
        return $template->delete();
    }

     /**
     * Create template from market place
     * @param  array $data 
     * @return bool       
     */
    public function createTemplateFromMarket(array $data)
    {
        $template = new Template;
        $template->user_id = $data['user_id'];
        $template->title = $data['title'];
        $template->content = $data['content'];
        $template->image = $data['image'] != null && $data['image'] != '' ? $data['image']: ['origin' => '', 'thumb' => ''];
        $template->type = 0;
        $template->source_file_pdf = $data['source_file_pdf'] != null ? $data['source_file_pdf']: '';
        $template->version = $data['version'];
        $template->clone = $data['clone'];
        $template->section = $data['section'];
        Template::makeSlug($template, false);

        return $template->save();
    }

     /**
     * Apply data into infomation section
     * @param  Template $template 
     * @param  string $section 
     * @param  array $data     
     * @return bool           
     */
    public function applyForInfo($template, $section, $data)
    {
        $template->content = $data['content'];
        $sec = $template->section;

        $template->section = array_set($sec, $section, $data['section']);

        return $template->save() ? $data['section'] : false;
    }
}