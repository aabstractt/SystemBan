<?php

/* Plugin By TheTrollDeveloper® 
Todos los derechos reservados:
  @TheTrollArtz
 Si quieres comprar algun complemento contactame por twitter*/

namespace System;

use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use pocketmine\event\player\PlayerChatEvent;

use System\Main;

class EventListener implements Listener {
	
	private $plugin;
	
	
	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	
	public function getPlugin(): Main {
		return $this->plugin;
	}
	
	
	
	public function onChat(PlayerChatEvent $ev){
		$player = $ev->getPlayer();
		$cfg = new Config($this->getPlugin()->getDataFolder() . "data.yml", Config::YAML);
		foreach($cfg->getAll() as $name => $value){
			if($value["address"] === $player->getAddress()){
				$ev->setCancelled(true);
				if($value["expire"]){
					$player->sendMessage(TextFormat::RED . "You have muted, expire in " . $this->getPlugin()->getMuteTime($player->getName()));
				}else{
					$player->sendMessage(TextFormat::RED . "You have muted.");
				}
			}
		}
	}
}