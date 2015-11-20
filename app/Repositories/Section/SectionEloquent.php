<?php
namespace App\Repositories\Section;

use App\Models\Section;
use App\Repositories\AbstractRepository;
use App\Repositories\Section\SectionInterface;

class SectionEloquent extends AbstractRepository implements SectionInterface
{
	protected $model;

	public function __construct(Section $section)
	{
		$this->model = $section;
	}

	public function getNameSections()
	{
		$names = $this->model->where('id', '!=', 1)->get()->pluck('name');
		$str = '';
		
		foreach ($names as $name) {
			$str .= $name.';';
		}

		$str = str_replace(' ', '', $str);

		return substr($str, 0, strlen($str) - 1);
	}

	public function forUser($id, $user_id)
	{
		return $this->model->whereUserId($user_id)->findOrFail($id);
	}
}