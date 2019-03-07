<?php

/* Plugin By TheTrollDeveloper® 
Todos los derechos reservados:
  @TheTrollArtz
 Si quieres comprar algun complemento contactame por twitter*/

namespace System\commands;

use pocketmine\utils\TextFormat;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

use System\Main;

class TMuteCommand extends PluginCommand {
	
	private $plugin;
	
	public function __construct(Main $plugin){
		parent::__construct("tmute", $plugin);
		$this->setDescription("Mute temporarily");
		$this->plugin = $plugin;
	}
	
	
	public function execute(CommandSender $sender, String $label, array $args): bool {
		if(!$sender instanceof Player) return "Error";
		if(!$sender->hasPermission("mute.execute")) return "You dont have permission";
		if(isset($args[0])){
			if(isset($args[1])){
				if(in_array($this->getPlugin()->stringToTimeString($args[1]), ["seconds", "minutes", "days"])){
					$target = $this->getPlugin()->getServer()->getPlayer($args[0]);
					if($target instanceof Player){
						$this->getPlugin()->setMute($target->getName(), $target->getAddress(), true, $args[1]);
						$target->sendMessage(TextFormat::GREEN . "You have been silenced for " . $this->getPlugin()->stringToIntTime($args[1]) . " " . $this->getPlugin()->stringToTimeString($args[1]) . ", by " . $sender->getName());
						$this->getPlugin()->getServer()->broadcastMessage(TextFormat::GREEN . $target->getName() . " has been silenced for " . $this->getPlugin()->stringToIntTime($args[1]) . " " . $this->getPlugin()->stringToTimeString($args[1]) . ", by " . $sender->getName());
					}
				}
			}
		}
		return true;
	}
}