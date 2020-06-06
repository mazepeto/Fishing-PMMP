<?php

// Fishing for PocketMine-MP (by cyphr3x)

declare(strict_types=1);

namespace cyphrex\Fishing;

use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {
	public static $instance;
	private static $fishing = [];

	public function onEnable() : void {
		self::$instance = $this;
		$this->saveDefaultConfig();
		$this->getScheduler()->scheduleRepeatingTask(new Schedule(), 40); # 2 seconds
		ItemFactory::registerItem(new FishingRod(), true);
		Entity::registerEntity(FishingHook::class, false, ['FishingHook', 'minecraft:fishinghook']);
	}

	public function onDamage(EntityDamageEvent $event) {
		$player = $event->getEntity();
		if (!$player instanceof Player || $event->getCause() !== EntityDamageEvent::CAUSE_FALL)
			return true;
		if ($player->getInventory()->getItemInHand()->getId() === ItemIds::FISHING_ROD)
			$event->setCancelled();
	}

	public static function getFishingHook(Player $player) : ?FishingHook {
		return self::$fishing[$player->getName()] ?? null;
	}

	public static function setFishingHook(?FishingHook $fish, Player $player) {
		self::$fishing[$player->getName()] = $fish;
	}
}
