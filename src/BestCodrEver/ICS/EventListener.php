<?php

namespace BestCodrEver\ICS;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\Form;

class EventListener implements Listener {

  private $plugin;

  public function __construct($plugin) {

    $this->plugin = $plugin;
    $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
  }
  
  public function onDamage(EntityDamageByEntityEvent $event){
    $player = $event->getPlayer()->getName();
    $damaged = $event->getDamager()->getName();
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
          switch ($data){
            case 0:
              $report = new CustomForm(function (Player $player, $data = null){
              if ($data === null){
                return true;
              }
                
              $report->setTitle("Instant Player Report");
              $default = null; //Recently hit player - null for now until I figure out how to set to recently hit player
              $report->addInput("Gamertag:", "Example123", $default, "reportPlayer");
              $report->addInput("Reason:", null, null, "reportReason");
              $report->addButton("Submit");
              $sender->sendForm($report);
              })
            break;
          }
          $guide->setTitle("Instant Player Report");
          $guide->setDescription(TextFormat::BOLD . "Note:\nFake reports are bannable.\nDo not waste our time.\n\nThis system notifies online staff of any reports. It is not our fault if we cannot get to you in time as our staff have other things to do.");
          $guide->addButton("OK");
        })
      }
    break;
  }
  return true;
}
  
  
}
