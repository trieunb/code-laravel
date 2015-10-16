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
		$user =  $this->getById($user_id);
		$user->firstname = $data['firstname'];
		$user->lastname = $data['lastname'];
		$user->email = $data['email'];
		$user->link_profile = $data['link_profile'];
		$user->infomation = $data['infomation'];
		$user->dob = $data['dob'];
		$user->gender = $data['gender'];
		$user->address = $data['address'];
		$user->soft_skill = $data['soft_skill'];
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
			->with(['user_educations', 'user_work_histories', 'user_skills', 'objectives'])
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
            'link_profile' => $data['publicProfileUrl'],
            'soft_skill' => config('soft-skill.question'),
            'token' => $token
        ]);
	}

    public function updateUserFromOauth($data, $token, $id)
    {
        $user = $this->getById($id);
        $user->firstname = $data['firstName'];
        $user->lastname = $data['lastName'];
        $user->email = $data['emailAddress'];
        $user->avatar = $data['pictureUrl'];
        $user->country = $data['location']['name'];
        $user->link_profile = $data['publicProfileUrl'];
        $user->token = $token;
        return $user->save();

    }

	public function getTemplateFromUser($user_id) {
		return $this->model
            ->with(['templates'])
            ->findOrFail($user_id);
	}

	/**
	 * Upload avatar
	 * @param  mixed $file    
	 * @param  int $user_id 
	 * @return mixed          
	 */
	public function uploadImage($file, $user_id)
	{
		$user = $this->getById($user_id);
		$user->avatar = User::uploadAvatar($file);

		return $user->save();
	}
}