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
	 * @param  int $user_id   
	 * @return mixed      
	 */
	public function saveFromApi($data, $user_id = null)
	{
		$user = $data['id'] ? $this->getById($data['id']) : new User;
		$user->firstname = $data['firstname'];
		$user->lastname = $data['lastname'];
		$user->email = $data['email'];
		$user->link_profile = $data['link_profile'];
		$user->inforation = $data['inforation'];
		$user->dob = Carbon\Carbon::createFromTimestamp($data['dob']);

		if ($data['avatar']) {
			$user->avatar = $data['avatar'];
		}
		$user->gender = $data['gender'];
		$user->address = $data['address'];
		$user->soft_skill = json_decode($data['soft_skill'], true);
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

	/**
	 * Get profile
	 * @param  int $user_id 
	 * @return mixed     
	 */
	public function getProfile($user_id)
	{
		return $this->model
			->with(['user_educations', 'user_work_histories', 'user_skills'])
			->findOrFail($user_id);
	}

	/**
	 * save data Register user
	 * @param  mixed $request 
	 * @param  string $token 
	 * @return void       
	 */
	public function registerUser($request, $token)
	{
		$data = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => \Hash::make($request->input('password')),
            'soft_skill' => config('soft-skill.question'),
            'token' => $token,
        ];

        $this->model->create($data);
	}

	/**
	 * Create User get inforation to Oauth2
	 * @param  array $data  
	 * @param  string $token 
	 * @return mixed        
	 */
	public function createUserFromOAuth($data, $token)
	{
		return $this->model->create([
            'linkedin_id' => $data['id'],
            'firstname' => $data['firstName'],
            'lastname' => $data['lastName'],
            'email' => $data['emailAddress'],
            'avatar' => $data['pictureUrl'],
            'country' => $data['location']["name"],
            'soft_skill' => config('soft-skill.question'),
            'token' => $token
        ]);
	}
}