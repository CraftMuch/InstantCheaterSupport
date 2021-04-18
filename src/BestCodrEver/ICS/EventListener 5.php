<?php

namespace BestCodrEver\ICS;

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

    // Why is this here, if not used anywhere?
    private $plugin;

    public function __construct($plugin)
    {

        $this->plugin = $plugin;

        // You can do the same straight from the plugin class onEnable
        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }


    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        // You don't need to use the switch, if theres only one condition to be met
        if ($cmd->getName() !== 'ireport') {
            return true;
        }

        if (!$sender instanceof Player) {
            $sender->sendMessage("This command must be run ingame.");
            return true;
        }

        if ($sender->hasPermission("ics.ban")) {
            $sender->sendMessage(TextFormat::RED . "You do not have permission to use the InstantCheaterSupport system.");
            return true;
        }

        // This is called Happy Path in programming: https://en.wikipedia.org/wiki/Happy_path
        // We deal with negative conditions and stop further execution leaving the positive response at the end
        // this eliminates the necessity and nesting if statements and improves readability (greatly)


        // Guide Form
        $this->sendGuideForm($sender);

        return true;
    }

    public function sendGuideForm(Player $player): void
    {
        $guide = new CustomForm([$this, 'handleGuideResponse']);
        $guide->setTitle("Instant Player Report");
        $guide->setDescription(TextFormat::BOLD . "Note:\nFake reports are bannable.\nDo not waste our time.\n\nThis system notifies online staff of any reports. It is not our fault if we cannot get to you in time as our staff have other things to do.");
        $guide->addButton("OK");

        $player->sendForm($guide);
    }

    public function sendReportForm(Player $player): void
    {
        $report = new CustomForm([$this, 'handleReportResponse']);
        $report->setTitle("Instant Player Report");

        $cause = $player->getLastDamageCause();
        $attacker = $cause instanceof EntityDamageByEntityEvent ? $cause->getDamager() : null;
        $default = $attacker instanceof Player ? $attacker->getName() : '';

        $report->addInput("Gamertag:", null, $default, "reportPlayer");
        $report->addInput("Reason:", null, null, "reportReason");
        $report->addButton("Submit");
        $player->sendForm($report);
    }

    public function handleGuideResponse(Player $player, $data = null) {
        if ($data === null) {
            return true;
        }
        if($data !== 0) return true;
        
        $this->sendReportForm($player);
    }

    public function handleReportResponse(Player $player, $data = null) {
        if ($data === null || $data !== 0) {
            return true;
        }
        
        $rp = $data["reportPlayer"];
        $rr = $data["reportReason"];

        if ($rp === null) {
            $hax = $attacker->getName();
        }
        $hax = $rp;

        if ($rr === null) {
            //Redo!
            $sender->sendMessage(TextFormat::RED . "Please enter your reason for reporting and retry.");
            return true;
        }

        $hackusation = $rr;
        
        $report = [[$sender->getName(), $hax, $hackusation]];
        $this->writeReport($report);

        $sender->sendMessage(TextFormat::GREEN . "You have successfully reported " . $hax . " for " . $hackusation);
    }


    public function decodeCSV(string $data, string $delimeter = ',', string $enclosure = '"', string $escape = "\\"): array
    {
        $lines = explode("\n", str_replace("\r", '', $data));
        foreach ($lines as $k => &$line) {
            $line = array_values(array_filter(str_getcsv($line, $delimeter, $enclosure, $escape)));
            if (empty($line)) unset($lines[$k]);
        }
        return $lines;
    }

}
