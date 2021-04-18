<?php

namespace BestCodrEver\ICS\Concerns;

use BestCodrEver\ICS\Contracts\ReportDatabase;

trait ReferencesDatabase
{

	/** @var ReportDatabase */
	protected $database;

	public function getDatabase(): ReportDatabase
	{
		return $this->database;
	}

}