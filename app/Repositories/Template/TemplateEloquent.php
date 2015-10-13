<?php
namespace App\Repositories\Template;

use App\Repositories\AbstractRepository;
use App\Repositories\Template\TemplateInterface;
use App\Template;

class TemplateEloquent extends AbstractRepository implements TemplateInterface
{
	protected $model;

	public function __construct(Template $model)
	{
		$this->model = $model;
	}


}