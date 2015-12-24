<?php
namespace App\Repositories\User;

use App\Events\GetCountryAndRegionFromLocationUser;
use App\Models\Objective;
use App\Models\Qualification;
use App\Models\Question;
use App\Models\Reference;
use App\Models\TemplateMarket;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserWorkHistory;
use App\Repositories\AbstractRepository;
use App\Repositories\User\UserInterface;
use Carbon\Carbon;
use DB;
use Khill\Lavacharts\Lavacharts;
use Lava;



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

        $result = $user->save();
        
		if ($result && $user->location != null) 
            event(new GetCountryAndRegionFromLocationUser($user));
        
        return $result;
	}

	/**
	 * Get profile
	 * @param  int $user_id
	 * @return mixed
	 */
	public function getProfile($user_id)
	{
		$data = $this->model
			->with(['user_educations' => function($q) {
                $q->orderBy('position');
            }, 'user_work_histories' => function($q) {
                $q->orderBy('position');
            }, 'questions' => function($q) {
            }, 'user_skills' => function($q) {
                $q->orderBy('position');
            }, 'references' => function($q) {
                $q->orderBy('position');
            }, 'objectives' => function($q) {
                $q->orderBy('position');
            }, 'qualifications' => function($q) {
                $q->orderBy('position');
            }
		 	])->findOrFail($user_id);

		$data->avatar = [
			'origin' => $data['avatar']['origin'] == null ? null: asset($data['avatar']['origin']),
			'thumb' => $data['avatar']['thumb'] == null ? null: asset($data['avatar']['thumb'])
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
            'location' => null,
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
        $avatar = isset($data['pictureUrls']['values']) ? [
            'origin' => $data['pictureUrls']['values'][0],
            'thumb' => $data['pictureUrls']['values'][0]]
        : null;
		return $this->model->create([
            'linkedin_id' => $data['id'],
            'firstname' => $data['firstName'],
            'lastname' => $data['lastName'],
            'email' => $data['emailAddress'],
            'avatar' => $avatar,
            'country' => $data['location']['name'],
            'link_profile' => $data['publicProfileUrl'],
            'soft_skill' => \Setting::get('questions'),
            'location' => null,
            'token' => $token
        ]);
	}

    public function updateUserFromOauth($data, $token, $id)
    {
        $user = $this->getById($id);

        $avatar = isset($data['pictureUrls']['values']) ? [
            'origin' => $data['pictureUrls']['values'][0],
            'thumb' => $data['pictureUrls']['values'][0]]
        : null;

        if (isset($data['id']))
            $user->linkedin_id = $data['id'];
        if (isset($data['firstName']))
            $user->firstname = $data['firstName'];
        if (isset($data['lastName']))
            $user->lastname = $data['lastName'];
        if (isset($data['emailAddress']))
            $user->email = $data['emailAddress'];
        if (isset($data['publicProfileUrl']))
            $user->link_profile = $data['publicProfileUrl'];
        if (isset($data['infomation']))
            $user->infomation = $data['infomation'];
        if (isset($data['birthday']))
            $user->dob = $data['birthday'];
        if (isset($data['gender']))
            $user->gender = $data['gender'];
        if (isset($data['pictureUrls']))
            $user->avatar = $avatar;
        if (isset($data['address']))
            $user->address = $data['address'];
        if (isset($data['soft_skill']))
            $user->soft_skill = $data['soft_skill'];
        if (isset($data['location']))
            $user->location = null;
        if (isset($data['phone-numbers']))
            $user->mobile_phone = $data['phone-numbers'];
        if (isset($data['home_phone']))
            $user->home_phone = $data['home_phone'];
        if (isset($data['city']))
            $user->city = $data['city'];
        if (isset($data['state']))
            $user->state = $data['state'];
        if (isset($data['location']))
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
		return $this->model->with(['templates'])->findOrFail($id);
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
        $avatar = isset($data['picture']) ? [
            'origin' => $data['picture']['data']['url'],
            'thumb' => $data['picture']['data']['url']
        ] : null;

        $birthday = isset($data['birthday'])
            ? Carbon::parse($data['birthday'])->format('Y-m-d')
            : false;
        $gender = '';
        if ($data['gender'] == "male")
            $gender = 0;
        elseif ($data['gender'] == "female")
            $gender = 1;
        else
            $gender = 2;

        return $this->model->create([
            'facebook_id' => $data['id'],
            'firstname' => $data['first_name'],
            'lastname' => $data['last_name'],
            'email' => isset($data['email']) ? $data['email'] : $data['id']."@facebook.com",
            'link_profile' => $data['link'],
            'gender' => $gender,
            'avatar' => $avatar,
            'soft_skill' => \Setting::get('questions'),
            'location' => null,
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
        $user->location = !$id ? null : !isset($data['location']) ? null: $data['location'];
        $user->soft_skill = \Setting::get('questions');
        if (isset($data['birthday']))
            $user->dob = Carbon::parse($data['birthday'])->format('Y-m-d');

        return $user->save();
    }

    /**
     * Get datatable of user
     * @return mixed
     */
    public function dataTable()
    {
        $users = $this->model
            ->whereDoesntHave('roles' , function($q) {
                $q->where('roles.slug', '=', 'admin');
            })->get();
        return \Datatables::of($users)
            ->addColumn('action', function($user) {
               return '<div class="btn-group text-center" role="group" aria-label="...">
                    <a class="btn btn-default" href="' .route('admin.user.get.detail', $user->id) . '"><i class="glyphicon glyphicon-eye-open"></i></a>
                </div>';
            })
            ->editColumn('firstname', function($user) {
                return $user->present()->name();;
            })
            ->addColumn('checkbox', function($user) {
                return '<input type="checkbox" value="'.$user->id.'" />';
            })
            ->editColumn('created_at', function($user) {
                return $user->created_at->format('Y-m-d');
            })
            ->make(true);
    }

    /**
     * Get Answers For User
     * @param  id $id
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function answerForUser($id)
    {
        return $this->getById($id)->questions;
    }

    /**
     * Create Or Update point of Question
     * @param int $id
     * @throw \Exception
     */
    public function setPointForAnswer($id, $data)
    {
        $user = $this->getById($id);

        if ( ! count($user->questions()->sync(
            Question::prepareQuestionsForSave($data)))
        )
            throw new \Exception('Error when save.');
    }

    public function getSectionProfile($id, $section)
    {
        switch ($section) {
            case 'education':
                return json_encode(['data' => ['education' => UserEducation::whereUserId($id)->get()]]);
                break;
            case 'work':
                return json_encode(['data' => ['work' => UserWorkHistory::whereUserId($id)->get()]]);
                break;
            case 'reference':
                return json_encode(['data' => ['reference' => Reference::whereUserId($id)->get()]]);
                break;
            case 'key_qualification':
                return json_encode(['data' => ['key_qualification' => Qualification::whereUserId($id)->get()]]);
                break;
            case 'objective':
                return json_encode(['data' => ['objective' => Objective::whereUserId($id)->get()]]);
                break;
            case 'name':
                return json_encode(['data' => $this->getById($id)->present()->name()]);
                break;
            case 'profile_website':
            case 'linkedin':
                return json_encode(['data' => $this->getById($id)->link_profile]);
                break;
            case 'availability':
                $status = null;
                foreach (\Setting::get('user_status') as $k => $v) {
                    if ($v['id'] == $this->getById($id)->status)
                        $status = $v;
                }
                return json_encode(['data' => $status]);
                break;
            case 'phone':
                return json_encode(['data' => $this->getById($id)->mobile_phone]);
                break;
            default:
                return json_encode(['data' =>$this->getById($id)->$section]);
                break;
        }
    }

    public function updateUserLogin($user, $token)
    {

        $this->model->update(['token' => $token], $user->id);
        return \Auth::login($user);
    }

    /**
     * Report user by month
     */
    public function reportUserMonth($year = null)
    {
        $user = $this->model->select(DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(id) AS count'))
                ->groupBy('month')
                ->orderBy('created_at', 'ASC');

        return is_null($year)
            ? $user->get()
            : $user->whereYear('created_at', '=', $year)->get();
    }

    public function reportUserGender()
    {
        $gender = ['Male' => 0, 'Female' => 0, 'Other' => 0];
        $users = $this->model->select(DB::raw('COUNT(*) as `count`,
            CASE WHEN gender = 0 THEN "Male"
               WHEN gender = 1 THEN "Female"
               WHEN gender = 2 OR gender is null THEN "Other"
               END as "gender_user"')
            )
            ->groupBy('gender_user')
            ->get();

        if (count($users) ==0) return '<h3>Not found data</h3>';
        
        $response = [];

        foreach ($users as $user) {
            $response[$user->gender_user] = (int)$user->count;
        }

        $genderDiff = array_diff_key($gender, $response);

        $responses = array_merge($response, $genderDiff);
        
        $lavaChart = new Lavacharts;
        $reason = $lavaChart->DataTable()
                ->addStringColumn('Reasons')
                ->addNumberColumn('Percent');        
        foreach ($responses as $name => $value) {
            $reason->addRow([$name, (int)$value]);
        }

        $pieChart = $lavaChart->PieChart('Chart')
            ->setOptions([
                'datatable' => $reason,
                'is3D' => true,
                'width' => 988,
                    'height' => 350,
                    'title' => 'Gender'
            ]);

        return $lavaChart->render('PieChart', 'Chart', 'chart_gender', true);
    }

    public function reportUserAge()
    {
        $response = [];
        $groupAge = ['Under 20 olds' => 0, '20-30 olds' => 0, 'Above 30 olds' => 0];
        $users = $this->model->select(DB::raw('COUNT(*) as count, CASE
                WHEN FLOOR(DATEDIFF(now(), dob ) / 365) < 20 OR dob is null OR dob = "0000-00-00" THEN "Under 20 olds"
                WHEN FLOOR(DATEDIFF(now(), dob) / 365) >= 20 AND FLOOR(DATEDIFF(now(), dob) / 365) <= 30 THEN "20-30 olds"
                WHEN FLOOR(DATEDIFF(now(), dob) / 365) > 30 THEN "Above 30 olds"
                END as "group_age"'
            ))
            ->groupBy('group_age')
            ->get();
        
        if (count($users) ==0) return '<h3>Not found data</h3>';

        foreach ($users as $user) {
            $response[$user->group_age] = intval($user->count);
        }

        $arrayDiff = array_diff_key($groupAge, $response);
        
        $responses = array_merge($response, $arrayDiff);
         
        $lavaChart = new Lavacharts;
        $reason = $lavaChart->DataTable()
                ->addStringColumn('Reasons')
                ->addNumberColumn('Percent');        
        foreach ($responses as $name => $value) {
            $reason->addRow([$name, (int)$value]);
        }

        $pieChart = $lavaChart->PieChart('Chart')
            ->setOptions([
                'datatable' => $reason,
                'is3D' => true,
                'width' => 988,
                'height' => 350,
                'title' => 'Age Group'
            ]);

        return $lavaChart->render('PieChart', 'Chart', 'chart_age', true);
    }

    public function reportUserRegion()
    {
        $lava = new Lavacharts;
        $userTable = $lava->DataTable();
        $userTable->addStringColumn('Region')
                    ->addNumberColumn('Users');



        $users = User::select('region',DB::raw('COUNT(id) as count'))
                ->groupBy('region')
                ->orderBy('created_at', 'DESC')
                ->get();

        if (count($users) ==0) return '<h3>Not found data</h3>';
        
        $user_count = User::count();
        foreach ($users as  $user) {
          /*  $region = '';
            switch ($user->country) {
                case '':
                    $region = 'Other';
                    break;
                case $user->country:
                    $region = $user->country;
                    break;
                default:
                    $region = 'Other';
                    break;
            }

            $rowData = [$region, (int)$user->count];
            $userTable->addRow($rowData);*/
            if ($user->region != null)
                $userTable->addRow([$user->region, (int)$user->count]);
            else $userTable->addRow(['Unknow', (int)$user->count]);
        }

        $chart_region = $lava->PieChart('Chart')
                    ->setOptions([
                        'datatable' => $userTable,
                        'is3D' => true,
                        'width' => 988,
                        'height' => 350,
                        'title' => 'Region'
                    ]);
        return $lava->render('PieChart', 'Chart', 'chart_region', true);
    }
}
