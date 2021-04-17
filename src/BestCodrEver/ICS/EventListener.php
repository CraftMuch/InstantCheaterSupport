<?php

namespace BestCodrEver\ICS;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\Form;

class EventListener implements Listener {

  private $plugin;

  public function __construct($plugin) {

    $this->plugin = $plugin;
    $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
  }
  
  public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
  switch($cmd->getName()) {
    case "ireport":
      if(!$sender instanceof Player) {
        $sender->sendMessage("This command must be run ingame.");
      } else { 
        //Form
        $guide = new CustomForm(function (Player $player, $data = null){
        if ($data === null){
           return true;
        }
          $guide->setTitle("Instant Player Report");
          $guide->setDescription(TextFormat::BOLD . "Note:\nFake reports are bannable.\nDo not waste our time.\n\nThis system notifies online staff of any reports. It is not our fault if we cannot get to you in time as our staff have other things to do.");
        }
      }
    break;
  }
  return true;
}
  
  
}
