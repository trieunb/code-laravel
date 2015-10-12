<?php

namespace App\Models;

use Bican\Roles\Traits\Slugable;
use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Traits\RoleHasRelations;
use Bican\Roles\Contracts\RoleHasRelations as RoleHasRelationsContract;

class Role extends Model implements RoleHasRelationsContract
{
    use Slugable, RoleHasRelations;
	/**
	 * Table name
	 * @var string
	 */
    protected $table = 'roles';

    /**
     * Create a new model instance.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if ($connection = config('roles.connection')) {
            $this->connection = $connection;
        }
    }

    /**
     * Prepare data for save role
     * @param  mied $rolesInput 
     * @return array             
     */
    public static function prepareRoleForSave($rolesInput)
    {
        $roles = static::all()->toArray();
        $prepareRoles = [];

        foreach ($rolesInput as $roleId) {
            array_walk($roles, function(&$value) use ($roleId, $prepareRoles){
                if ($value['id'] == $roleId) {
                    $prepareRoles[] = $roleId;
                }
            });
        }

        return $prepareRoles;
    }

    /**
     * prepare data role for display
     * @param  mixed $roles 
     * @return mixed        
     */
    public static function prepareRoleForDisplayEdit($role)
    {
        $prepareRoles = static::all()->toArray();

        foreach ($role as $field) {
            array_walk($prepareRoles, function(&$value) use ($field){
                if ($field->name == $value['name']) {
                    $value['checked'] = true;
                }
            });
        }

        return $prepareRoles;
    }
}
