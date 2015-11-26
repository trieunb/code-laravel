<?php
namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\AbstractRepository;
use App\Repositories\User\UserInterface;
use Carbon\Carbon;



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
		if (isset($data['firstname']))
			$user->firstname = $data['firstname'];
		if (isset($data['lastname']))
			$user->lastname = $data['lastname'];
		if (isset($data['email']))
			$user->email = $data['email'];
        if (isset($data['status']))
            $user->status = $data['status'];
		if (isset($data['link_profile']))
			$user->link_profile = $data['link_profile'];
		if (isset($data['infomation']))
			$user->infomation = $data['infomation'];
		if (isset($data['dob']))
			$user->dob = $data['dob'];
		if (isset($data['gender']))
			$user->gender = $data['gender'];
		if (isset($data['address']))
			$user->address = $data['address'];
		if (isset($data['soft_skill']))
			$user->soft_skill = $data['soft_skill'];
		if (isset($data['mobile_phone']))
			$user->mobile_phone = $data['mobile_phone'];
		if (isset($data['home_phone']))
			$user->home_phone = $data['home_phone'];
		if (isset($data['city']))
			$user->city = $data['city'];
		if (isset($data['location']))
			$user->location = $data['location'];
		if (isset($data['state']))
			$user->state = $data['state'];
		if (isset($data['country']))
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
		$data = $this->model
			->with(['user_educations', 'user_work_histories',
			 	'user_skills', 'references', 'objectives', 'qualifications'
		 	])->findOrFail($user_id);

		$data->avatar = [
			'origin' => $data['avatar']['origin'] == null ?: asset($data['avatar']['origin']),
			'thumb' => $data['avatar']['thumb'] == null ?: asset($data['avatar']['thumb'])
		];
		$status = null;
		foreach (\Setting::get('user_status') as $k => $v) {
			if ($v['id'] == $data->status)
				$status = $v;
		}
		

		$data->status = $data->status != 0 && $data->status != null ? $status : null;

		return $data;
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
            'soft_skill' => \Setting::get('questions'),
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
        $avatar = [
            'origin' => $data['pictureUrls']['values'][0],
            'thumb' => $data['pictureUrls']['values'][0]
        ];
		return $this->model->create([
            'linkedin_id' => $data['id'],
            'firstname' => $data['firstName'],
            'lastname' => $data['lastName'],
            'email' => $data['emailAddress'],
            'avatar' => $avatar,
            'country' => $data['location']['name'],
            'link_profile' => $data['publicProfileUrl'],
            'soft_skill' => \Setting::get('questions'),
            'location' => ['long' => null, 'last' => null],
            'token' => $token
        ]);
	}

    public function updateUserFromOauth($data, $token, $id)
    {
        $user = $this->getById($id);
        $avatar = [
            'origin' => $data['pictureUrls']['values'][0],
            'thumb' => $data['pictureUrls']['values'][0]
        ];

        if (isset($data['id']))
            $user->linkedin_id = $data['id'];
        if (isset($data['firstName']))
            $user->firstname = $data['firstName'];
        if (isset($data['lastName']))
            $user->lastname = $data['lastName'];
        if (isset($data['emailAddress']))
            $user->email = $data['emailAddress'];
        if (isset($data['link_profile']))
            $user->link_profile = $data['publicProfileUrl'];
        if (isset($data['infomation']))
            $user->infomation = $data['infomation'];
        if (isset($data['dob']))
            $user->dob = $data['dob'];
        if (isset($data['gender']))
            $user->gender = $data['gender'];
        if (isset($data['pictureUrls']))
            $user->avatar = $avatar;
        if (isset($data['address']))
            $user->address = $data['address'];
        if (isset($data['soft_skill']))
            $user->soft_skill = $data['soft_skill'];
        if (isset($data['location']))
            $user->location = $data['location'];
        if (isset($data['mobile_phone']))
            $user->mobile_phone = $data['mobile_phone'];
        if (isset($data['home_phone']))
            $user->home_phone = $data['home_phone'];
        if (isset($data['city']))
            $user->city = $data['city'];
        if (isset($data['state']))
            $user->state = $data['state'];
        if (isset($data['country']))
            $user->country = $data['location']['name'];
        $user->token = $token;

        return $user->save();
    }
    
    /**
	 * Get template for user id
	 * @param  int $id
	 * @return mixed
	 */
	public function getTemplateFromUser($id) {
		return $this->model
            ->with(['templates'])
            ->findOrFail($id);
	}

    /**
     * get all template from market place
     */
    public function getAlltemplatesFromMarketPlace($user_id)
    {
        return $this->model
            ->with(['template_markets'])
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

		$image = [ 
			'origin' => asset($user->avatar['origin']),
			'thumb' => asset($user->avatar['thumb'])
		];
		
		return $user->save() ? $image : '';
	}

	/**
	 * Edit Status
	 * @param  int $id     
	 * @param  int $status 
	 * @return bool         
	 */
	public function editStatus($id, $status)
	{
		if ( !in_array((int)$status, [1, 2, 3]))
			return null;

		$user = $this->getById($id);
		$user->status = $status;
		$result = $user->save();
		
		if ($result) {
			$status = null;
			foreach (\Setting::get('user_status') as $k => $v) {
				if ($v['id'] == $user->status)
					$status = $v;
			}
		}
		
		return $user->save() ? $status : null;
	}
    /**
     * Remove photo
     * @param  int $id 
     * @return bool     
     */
    public function removePhoto($id)
    {
        $user = $this->getById($id);

        try {
            \File::delete(public_path($user->avatar['origin']));
            \File::delete(public_path($user->avatar['thumb']));
            $user->avatar = ['origin' => null, 'thumb' => null];

            return $user->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function createUserFacebook($data, $token)
    {
        $avatar = [
            'origin' => $data['picture']['data']['url'],
            'thumb' => $data['picture']['data']['url']
        ];

        $birthday = isset($data['birthday'])
            ? Carbon::parse($data['birthday'])->format('Y-m-d')
            : false;

        return $this->model->create([
            'facebook_id' => $data['id'],
            'firstname' => $data['first_name'],
            'lastname' => $data['last_name'],
            'email' => $data['email'],
            'link_profile' => $data['link'],
            'gender' => ($data['gender'] == "male") ? 0 : 1,
            'avatar' => $avatar,
            'soft_skill' => \Setting::get('questions'),
            'dob' => $birthday,
            'token' => $token
        ]);
    }

    public function updateUserFacebook($data, $token, $id)
    {
        $user = $this->getById($id);

        $avatar = [
            'origin' => $data['picture']['data']['url'],
            'thumb' => $data['picture']['data']['url']
        ];

        if (isset($data['id']))
            $user->facebook_id = $data['id'];
        if (isset($data['first_name']))
            $user->firstname = $data['first_name'];
        if (isset($data['last_name']))
            $user->lastname = $data['last_name'];
        if (isset($data['email']))
            $user->email = $data['email'];
        if (isset($data['link']))
            $user->link_profile = $data['link'];
        if (isset($data['gender']))
            $user->gender = $data['gender'];
        if (isset($data['picture']))
            $user->avatar = $avatar;
        $user->location = !$id ? ['long' => null, 'last' => null] : !isset($data['location'])?: $data['location'];
        $user->soft_skill = \Setting::get('questions');
        if (isset($data['birthday']))
            $user->dob = Carbon::parse($data['birthday'])->format('Y-m-d');
        
        return $user->save();
    }
}