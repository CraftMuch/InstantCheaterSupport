<?php

namespace BestCodrEver\ICS\Concerns;

use BestCodrEver\ICS\Main;

trait ReferencesMain
{

	protected $main;

	public function getMain(): Main
	{
		return $this->main;
	}

}