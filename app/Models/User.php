<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Role;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\UserWorkHistory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Bican\Roles\Traits\HasRoleAndPermission;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract,
                                    HasRoleAndPermissionContract
{
    use Authenticatable, Authorizable, CanResetPassword, HasRoleAndPermission;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'linkedin_id',
        'firstname',
        'lastname',
        'email',
        'dob',
        'avatar',
        'address',
        'mobile_phone',
        'home_phone',
        'city',
        'state',
        'country',
        'password',
        'oauth_token',
        'exp_time_token'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * path folder uploads
     * @var string
     */
    public static $path = 'uploads/';

    public static $img_width = 200;
    public static $img_height = 200;
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

  

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_educations()
    {
        return $this->hasMany(UserEducation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */ 
    public function user_skills()
    {
        return $this->hasMany(UserSkill::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_work_histories()
    {
        return $this->hasMany(UserWorkHistory::class);
    }

    /**
     * Rename Image after upload 
     * @param  mixed $request 
     * @return string          
     */
    public static function renameImage($request)
    {
        $filename = explode('.', $request->getClientOriginalName());

        return time().md5($filename[0]).'.'.end($filename);
    }

}
