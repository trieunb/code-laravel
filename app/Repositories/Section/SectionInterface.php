<?php
namespace App\Repositories\Section;

use App\Repositories\Repository;

interface SectionInterface extends Repository
{
	public function getNameSections();

	public function forUser($id, $user_id);
}