<?php

/* Plugin By TheTrollDeveloper® 
Todos los derechos reservados:
  @TheTrollArtz
 Si quieres comprar algun complemento contactame por twitter*/

namespace System;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use System\commands\MuteCommand;
use System\commands\TMuteCommand;

use System\EventListener;

use System\task\QueuedMute;

class Main extends PluginBase implements Listener {
	
	private $data = ["time" => []];
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("SystemBan has been enable");
		@mkdir($this->getDataFolder());
		$this->getScheduler()->scheduleRepeatingTask(new QueuedMute($this), 1);
		$this->getServer()->getCommandMap()->register("mute", new MuteCommand($this));
		$this->getServer()->getCommandMap()->register("tmute", new TMuteCommand($this));
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
	}
	
	
	public function setMute(String $name, String $ip, bool $expire, String $time = null){
		$date = date("H:i:s d-m-y");
		if($expire) $date = date("H:i:s d-m-y", strtotime("+ {$this->stringToIntTime($time)} {$this->stringToTimeString($time)}"));
		$cfg = new Config($this->getDataFolder() . "data.yml", Config::YAML);
		$cfg->set($name, [
		"expire" => $expire,
		"date" => $date,
		"dateMute" => date("H:i:s d-m-y"),
		"address" => $ip]);
		$cfg->save();
	}
	
	public function setMuteTime(String $name, String $time){
		$this->data["time"][$name] = $time;
	}
	
	public function getMuteTime(String $name): String {
		return $this->data["time"][$name];
	}
	
	
	
	public function stringToIntTime(String $format): int {
		$formatChars = str_split($format);
		$currentCharacters = null;
		for($i = 0; $i < count($formatChars); $i++){
			if(is_numeric($formatChars[$i])){
				$currentCharacters .= $formatChars[$i];
				continue;
			}
		}
		return $currentCharacters;
	}
	
	
	public function stringToTimeString(String $format): String {
		$until = null;
		$formatChars = str_split($format);
		$currentCharacters = null;
		for($i = 0; $i < count($formatChars); $i++){
			switch(strtolower($formatChars[$i])){
				case "s":
				$until = "seconds";
				break;
				case "m":
				$until = "minutes";
				break;
				case "h":
				$until = "hours";
				break;
				case "d":
				$until = "days";
				break;
			}
		}
		return $until;
	}
	
	
	public function timeDateToString(String $startDate, String $endDate): String {
		$timeMonth = null;
		$timeWeek = null;
		$timeDay = null;
		$timeHour = null;
		$timeMinute = null;
		$timeSecond = null;
		$diff = strtotime($endDate)-strtotime($startDate);
		$temp = $diff/86400;
		$days = floor($temp);
		$temp = 24*($temp-$days);
		$hours = floor($temp);
		$temp = 60*($temp-$hours);
		$minutes = floor($temp);
		$temp = 60*($temp-$minutes);
		$seconds = floor($temp);
		if($days > 0) $timeDay = $days . " days, ";
		if($hours > 0) $timeHour = $hours . " hours, ";
		if($minutes > 0) $timeMinute = $minutes . " minutes, ";
		if($seconds > 0) $timeSecond = $seconds . " seconds";
		return $timeDay . $timeHour . $timeMinute . $timeSecond;
	}
}