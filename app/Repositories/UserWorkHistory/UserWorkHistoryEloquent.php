<?php
namespace App\Repositories\UserWorkHistory;

use App\Models\UserWorkHistory;
use App\Repositories\AbstractRepository;
use App\Repositories\UserWorkHistory\UserWorkHistoryInterface;

class UserWorkHistoryEloquent extends AbstractRepository implements UserWorkHistoryInterface
{
	protected $model;

	public function __construct(UserWorkHistory $user_work_history)
	{
		$this->model = $user_work_history;
	}
}