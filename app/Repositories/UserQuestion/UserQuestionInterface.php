<?php
namespace App\Repositories\UserQuestion;

use App\Repositories\Repository;

interface UserQuestionInterface extends Repository
{

    public function saveUserAnswer($data, $user_id);

}