<?php
namespace App\Repositories\Category;

use App\Events\UpdatePathWhenSaved;
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

	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id  if $id == null => create else update
	 * @return mixed      
	 */
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
		$category->type = $request->get('type');
		$category->parent_id = $request->get('parent_id');
		
		if ($id) {
			$category->path = Category::getPathParent($request->get('parent_id')).$id;
		} 

		$result = $category->save();

		if ($result && !$id) {
			event(new UpdatePathWhenSaved($result->id, $result->parent_id));
		}

		return $result;
	}

	
}