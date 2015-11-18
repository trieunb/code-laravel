<?php
namespace App\Presenter;

use Laracasts\Presenter\Presenter;

class TemplateMarketPresenter extends Presenter
{
	public function contentPresent()
	{
		$this->content = str_replace("contenteditable='true'", '', $this->content);

		return $this->content;
	}
}