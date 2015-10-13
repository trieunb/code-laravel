<?php
namespace App\Repositories\Template;

use App\Repositories\Repository;

interface TemplateInterface extends Repository
{
	public function saveTemplate($data);
}