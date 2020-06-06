<?php

declare(strict_types=1);

namespace cyphrex\Fishing;

use pocketmine\entity\Entity;
use pocketmine\item\Tool;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\Player;

class FishingRod extends Tool {
	public function __construct() {
		parent::__construct(self::FISHING_ROD, 0, 'Fishing Rod');
	}

	public function getMaxStackSize() : int {
		return 1;
	}

	public function getMaxDurability() : int {
		return 65;
	}

	public function getFuelTime() : int {
		return 300;
	}

	public function onAttackEntity(Entity $victim) : bool{
		return $this->applyDamage(1);
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

		return true;
	}
}
