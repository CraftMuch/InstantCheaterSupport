<?php

namespace BestCodrEver\ICS\Data;

use BestCodrEver\ICS\Concerns\ReferencesMain;
use BestCodrEver\ICS\Contracts\ReportDatabase;
use BestCodrEver\ICS\Main;
use SQLite3;

class SQLite3DataProvider implements ReportDatabase
{
	use ReferencesMain;

	/** @param SQLite3 */
	protected $database;

	public function __construct(Main $main)
	{
		$this->main = $main;

		$this->setup();
	}

	public function setup(): void
	{
		if(!file_exists($file = $this->main->getDataFolder() . 'reports.db')) {
			touch($file);
			echo 'reports created'.PHP_EOL;
		}

		$this->database = new SQLite3($file);

		$query = stream_get_contents($h = $this->getMain()->getResource("sql3_setup.sql"));
        @fclose($h);

		$this->database->query($query);
	}

	public function saveReport(string $by, string $who, string $reason): bool
	{
		$stmt = $this->database->prepare("INSERT INTO reports (snitch, hacker, reason) VALUES (:snitch, :hacker, :reason);");
		$stmt->bindParam('snitch', $by);
		$stmt->bindParam('hacker', $who);
		$stmt->bindParam('reason', $reason);
		$result = $stmt->execute();
		
		if($result) {
			return $result->finalize();
		}
		return false;
	}

	public function readReports(): array
	{
		$results = $this->database->query("SELECT * from reports;");
		if(!$results) {
			return [];
		}

		$rows = [];
		while($row = $results->fetchArray(SQLITE3_ASSOC)) {
			$rows[] = $row;
		}
		return $rows;
	}

	public function close(): void
	{
		if($this->database instanceof SQLite3) {
			$this->database->close();
		}
	}

}