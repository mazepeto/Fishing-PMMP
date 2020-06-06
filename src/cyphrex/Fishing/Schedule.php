<?php

declare(strict_types=1);

namespace cyphrex\Fishing;

use cyphrex\Fishing\Main;
use pocketmine\scheduler\Task;

class Schedule extends Task {
	private $instance;

	public function __construct() {
		$this->instance = Main::$instance;
	}

	public function onRun(int $currentTick) {
			/*
    foreach($this->instance->getServer()->getLevels() as $level) {
      foreach ($level->getPlayers() as $player)
        $this->players[] = $player;
    }
  public function onClickAir(Player $player, Vector3 $directionVector) : bool {
    if(Main::getFishingHook($player) === null) {
      $nbt = Entity::createBaseNBT($player);
      $hook = Entity::createEntity('FishingHook', $player->level, $nbt, $player);
      $hook->spawnToAll();
    } else {
      $hook = Main::getFishingHook($player);
      $hook->handleHookRetraction();
    }

    $player->broadcastEntityEvent(AnimatePacket::ACTION_SWING_ARM);
			*/
	}
}
