<?php
namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\AbstractRepository;
use App\Repositories\Category\CategoryInterface;

class CategoryEloquent extends AbstractRepository implements CategoryInterface
{
	protected $model;

	public function __construct(Category $category)
	{
		$this->model = $category;
	}

	public function save($request, $id = null)
	{
		$category = $id ? $this->getById($id) : new Category;

		if ( !$id) $category->user_id = \Auth::user()->id;

		$category->name = $request->get('name');
		$category->slug = toSlug($request->get('name'));
		$category->meta = json_encode([
			'meta_description' => $request->get('meta_description'),
			'meta_keyword' => $request->get('meta_keyword')
		]);
		$category->parent_id = $request->get('parent_id');
		
		if (count($this->model->all()) == 0) {
			$category->path = '0-';
		} else {
			
		}

	}
}