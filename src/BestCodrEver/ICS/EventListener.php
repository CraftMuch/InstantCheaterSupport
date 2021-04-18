<?php

namespace BestCodrEver\ICS;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\utils\Config;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\Form;

class EventListener implements Listener
{


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


        // Guide Form
        $this->sendGuideForm($sender);

        return true;
    }


}
