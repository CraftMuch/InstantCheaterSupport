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
  
  
  public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
  switch($cmd->getName()) {
    case "ireport":
      if(!$sender instanceof Player) {
        $sender->sendMessage("This command must be run ingame.");
      } else {
          if ($sender->hasPermission("ics.ban")){
            $sender->sendMessage(TextFormat::RED . "You do not have permission to use the InstantCheaterSupport system.");
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
                $cause = $sender->getLastDamageCause(); 
                if($cause instanceof EntityDamageByEntityEvent){
                  $attacker = $cause->getDamager();
                }
                switch ($data){
                case 0:
                    $rp = $data["reportPlayer"];
                    $rr = $data["reportReason"];
                    //Save player report and run some checks on it
                    if ($rp === null){
                      //Report last damager
                      $hax = $attacker;
                    }else {
                      //Report input
                      $hax = $rp;
                    }
                    if ($rr === null){
                      //Redo!
                      $sender->sendMessage(TextFormat::RED . "Please enter your reason for reporting and retry.");
                    }else {
                      $hackusation = $rr;
                    }
                break;
                }
              $report->setTitle("Instant Player Report");
              $default = $attacker;
              $report->addInput("Gamertag:", null, $default, "reportPlayer");
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
      }}}
    break;
  }
  return true;
}
  
  
}
