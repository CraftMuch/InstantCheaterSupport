<?php

namespace BestCodrEver\ICS;

use BestCodrEver\ICS\Concerns\ReferencesDatabase;
use BestCodrEver\ICS\Concerns\ReferencesMain;
use BestCodrEver\ICS\Contracts\ReportDatabase;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\utils\TextFormat;
use pocketmine\Player;


class ReportForm
{
	use ReferencesMain, ReferencesDatabase;

	public function __construct(Main $main, ReportDatabase $database)
	{
		$this->main = $main;
		$this->database = $database;
	}

	public function sendGuideForm(Player $player): void
	{
		$guide = new SimpleForm([$this, 'handleGuideResponse']);
		$guide->setTitle("Instant Player Report");
		$guide->setContent(TextFormat::BOLD . "Note:\nFake reports are bannable.\nDo not waste our time.\n\nThis system notifies online staff of any reports. It is not our fault if we cannot get to you in time as our staff have other things to do.");
		$guide->addButton('OK');

		$player->sendForm($guide);
	}

	public function sendReportForm(Player $player): void
	{
		$report = new CustomForm([$this, 'handleReportResponse']);
		$report->setTitle("Instant Player Report");

		$cause = $player->getLastDamageCause();
		$attacker = $cause instanceof EntityDamageByEntityEvent ? $cause->getDamager() : null;
		$default = $attacker instanceof Player ? $attacker->getName() : '';

		$report->addInput("Gamertag:", 'Player name', $default, "reportPlayer");
		$report->addInput("Reason:", 'Enter the reason of report', null, "reportReason");
		$player->sendForm($report);
	}

	public function handleGuideResponse(Player $player, $data = null)
	{
		if ($data === null) {
			return true;
		}
		if ($data !== 0) return true;

		$this->sendReportForm($player);
	}

	public function handleReportResponse(Player $player, ?array $data)
	{
		if ($data === null) {
			return true;
		}

		$reportedPlayer = $data["reportPlayer"];
		$reportReason = $data["reportReason"];

		if(empty($reportedPlayer) || is_null($reportedPlayer)) {
			$player->sendMessage(TextFormat::RED . "Please enter the name of the player to report.");
			return true;
		}

		if (empty($reportReason) || is_null($reportReason)) {
			$player->sendMessage(TextFormat::RED . "Please enter your reason for reporting and retry.");
			return true;
		}

		$this->database->saveReport($player->getName(), $reportedPlayer, $reportReason);

		$player->sendMessage(TextFormat::GREEN . "You have successfully reported '{$reportedPlayer}' for '{$reportReason}'");
	}


}
