<?php
namespace App\Repositories\Template;

use App\Repositories\AbstractRepository;
use App\Repositories\Template\TemplateInterface;
use App\Models\Template;

class TemplateEloquent extends AbstractRepository implements TemplateInterface
{
	protected $model;

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

        $user_template->name = $dataPrepareSave['name'];
        $user_template->template = $dataPrepareSave['template'];

        return $user_template->save();
    }
}