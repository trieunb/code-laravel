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
    public function saveTemplate($data)
    {
        // $template = $data['id'] ? 
        // $this->getById($data['id']) : 
        // new Template;
        $template->user_id = $data['user_id'];
        $template->name = $data['name'];
        $template->template $data['template'];
        return $template->save();
    }

}