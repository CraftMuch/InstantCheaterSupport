<?php

namespace BestCodrEver\ICS\Contracts;

interface ReportDatabase
{

	public function setup(): void;

	public function saveReport(string $by, string $who, string $reason): bool;

	public function readReports(): array;

	public function close(): void;

}