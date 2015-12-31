<?php
namespace App\Http\ViewComposers;

use App\Models\User;
use Illuminate\Contracts\View\View;

class UserComposer
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function compose(View $view)
    {
        $users = $this->user->select('id', \DB::raw('CONCAT(firstname, " ", lastname) as name'))
            ->lists('name', 'id');

        $view->with('list_users', $users->all());
    }
}