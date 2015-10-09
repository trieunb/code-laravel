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
	 * @param  mixed $data 
	 * @param  int $id   
	 * @return mixed      
	 */
	public function save($data, $id = null)
	{
		$user = $id ? $this->getById($id) : new User;
		$user->firstname = $data['firstname'];
		$user->lastname = $data['lastname'];
		$user->email = $data['email'];
		$user->dob = $data['dob'];

		if ($data['avatar']) {
			$user->avatar = $data['avatar'];
		}

		$user->address = $data['address'];
		$user->mobile_phone = $data['mobile_phone'];
		$user->home_phone = $data['home_phone'];
		$user->city = $data['city'];
		$user->state = $data['state'];
		$user->country = $data['country'];
		
		if (array_key_exists('password', $data)) {
			$user->password = bcrypt($data['password']);
		}

		return $user->save();
	}
}