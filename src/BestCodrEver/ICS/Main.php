<?php

namespace BestCodrEver\ICS;

use BestCodrEver\ICS\Contracts\ReportDatabase;
use BestCodrEver\ICS\Data\SQLite3DataProvider;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\Player;


class Main extends PluginBase
{

	/** @var ReportDatabase */
	protected $database;

	/** @var ReportForm */
	protected $forms;

	public function onEnable()
	{
		$this->database = new SQLite3DataProvider($this);
		$this->forms = new ReportForm($this, $this->database);
	}

	public function onDisable()
	{
		if ($this->database instanceof ReportDatabase) {
			$this->database->close();
		}
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
	{

		if ($cmd->getName() !== 'ireport') {
			return true;
		}

		if (!$sender instanceof Player) {
			$sender->sendMessage("This command must be run ingame.");
			return true;
		}

		if (!$sender->hasPermission("ics.idkidontneedbancommandthen")) {
			$sender->sendMessage(TextFormat::RED . "You do not have permission to use the InstantCheaterSupport system.");
			return true;
		}

		$this->forms->sendGuideForm($sender);

		return true;
	}

}
