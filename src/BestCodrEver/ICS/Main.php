<?php

namespace BestCodrEver\ICS;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use BestCodrEver\ICS\EventListener;
use jojoe77777\FormAPI\Form;
use jojoe77777\FormAPI\CustomForm;

class Main extends PluginBase{
  
  public function onEnable(){
		    $this->eventListener = new EventListener($this);
    		$this->getLogger()->info("System Enabled");
  }

}
