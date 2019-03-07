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

class MuteCommand extends PluginCommand {
	
	private $plugin;
	
	public function __construct(Main $plugin){
		parent::__construct("mute", $plugin);
		$this->setDescription("Mute permanently");
		$this->plugin = $plugin;
	}
	
	
	public function execute(CommandSender $sender, String $label, array $args): bool {
		if(!$sender instanceof Player) return "Error";
		if(!$sender->hasPermission("mute.execute")) return "You dont have permission";
		if(isset($args[0])){
			$target = $this->getPlugin()->getServer()->getPlayer($args[0]);
			if($target instanceof Player){
				$this->getPlugin()->setMute($target->getName(), $target->getAddress(), false);
				$target->sendMessage(TextFormat::GREEN . "You have been silenced permanently by " . $sender->getName());
				$this->getPlugin()->getServer()->broadcastMessage(TextFormat::GREEN . $target->getName() . " has been silenced permanently by " . $sender->getName());
			}
		}
		return true;
	}
}