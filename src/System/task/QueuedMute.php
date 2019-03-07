<?php

/* Plugin By TheTrollDeveloper® 
Todos los derechos reservados:
  @TheTrollArtz
 Si quieres comprar algun complemento contactame por twitter*/

namespace System\task;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use System\Main;

class QueuedMute extends Task {
	
	private $plugin;
	
	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	
	
	private function getPlugin(): Main {
		return $this->plugin;
	}
	
	
	
	public function onRun(int $currentTick){
		$cfg = new Config($this->getPlugin()->getDataFolder() . "data.yml", Config::YAML);
		foreach($cfg->getAll() as $name => $value){
			if($value["expire"] == true){
				$target = $this->getPlugin()->getServer()->getPlayer($name);
				if($target instanceof Player){
					$this->getPlugin()->setMuteTime($target->getName(), $this->getPlugin()->timeDateToString(date("H:i:s d-m-y"), $value["date"]));
					if(date("H:i:s d-m-y") > $value["date"]){
						$this->getPlugin()->getLogger()->warning($name . " has been unmuted for expire");
						$target->sendMessage(TextFormat::GREEN . "Your muted time is over, you have been unmuted by CONSOLE!");
						$cfg->remove($name);
						$cfg->save();
					}
				}else{
					if(date("H:i:s d-m-y") > $value["date"]){
						$this->getPlugin()->getLogger()->warning($name . " has been unmuted for expire");
						$cfg->remove($name);
						$cfg->save();
					}
				}
			}
		}
	}
}