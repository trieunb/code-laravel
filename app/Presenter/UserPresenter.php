<?php
namespace App\Presenter;

use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter
{
	public function name()
	{
		return $this->firstname. ' ' . $this->lastname;
	}
}