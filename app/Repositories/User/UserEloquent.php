<?php
namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\AbstractRepository;
use App\Repositories\User\UserInterface;


class UserEloquent extends AbstractRepository implements UserInterface
{
	protected $model;

	public function __construct(User $user)
	{
		$this->model = $user;
	}

	/**
	 * Create or Update data
	 * @param  mixed $request 
	 * @param  int $id   
	 * @return mixed      
	 */
	public function save($request, $id = null)
	{
		$user = $id ? $this->getById($id) : new User;
		$user->firstname = $request->get('firstname');
		$user->lastname = $request->get('lastname');
		$user->email = $request->get('email');
		$user->dob = $request->get('dob');

		if ($request->hasFile('avatar')) {
			$filename = User::renameImage($request->file('avatar'));
			$image = \Image::make(public_path(User::$path.$filename));

			if ($image->resize(User::$img_with, User::$img_height)->save()) {
				$user->avatar = User::$path.$filename;
			}
		}

		$user->address = $request->get('address');
		$user->mobile_phone = $request->get('mobile_phone');
		$user->home_phone = $request->get('home_phone');
		$user->city = $request->get('city');
		$user->state = $request->get('state');
		$user->country = $request->get('country');
		
		if ($request->has('password')) {
			$user->password = bcrypt($request->get('password'));
		}

		return $user->save();
	}
}