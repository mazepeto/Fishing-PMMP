<?php

declare(strict_types=1);

namespace cyphrex\Fishing;

use cyphrex\Fishing\Main;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Projectile;
use pocketmine\level\Level;
use pocketmine\math\RayTraceResult;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\Random;

class FishingHook extends Projectile {
	public const NETWORK_ID = self::FISHING_HOOK;
	public $height = 0.25;
	public $width = 0.25;
	protected $gravity = 0.1;

	public function __construct(Level $level, CompoundTag $nbt, ?Entity $owner = null) {
		parent::__construct($level, $nbt, $owner);
		if ($owner instanceof Player) {
			$this->setPosition($this->add(0, $owner->getEyeHeight() - 0.1));
			$this->setMotion($owner->getDirectionVector()->multiply(0.4));
			Main::setFishingHook($this, $owner);
			$this->handleHookCasting($this->motion->x, $this->motion->y, $this->motion->z, 1.5, 1.0);
		}
	}

	public function handleHookCasting(float $x, float $y, float $z, float $f1, float $f2) {
		$rand = new Random();
		$f = sqrt($x * $x + $y * $y + $z * $z);
		$x = $x / (float) $f;
		$y = $y / (float) $f;
		$z = $z / (float) $f;
		$x = $x + $rand->nextSignedFloat() * 0.007499999832361937 * (float) $f2;
		$y = $y + $rand->nextSignedFloat() * 0.007499999832361937 * (float) $f2;
		$z = $z + $rand->nextSignedFloat() * 0.007499999832361937 * (float) $f2;
		$x = $x * (float) $f1;
		$y = $y * (float) $f1;
		$z = $z * (float) $f1;
		$this->motion->x += $x;
		$this->motion->y += $y;
		$this->motion->z += $z;
	}

	public function entityBaseTick(int $tickDiff = 1) : bool {
		$hasUpdate = parent::entityBaseTick($tickDiff);
		$owner = $this->getOwningEntity();
		if ($owner instanceof Player) {
			if (!$owner->getInventory()->getItemInHand() instanceof FishingRod or !$owner->isAlive() or $owner->isClosed())
				$this->flagForDespawn();
		} else {
			$this->flagForDespawn();
		}

		return $hasUpdate;
	}

	public function onHitEntity(Entity $entityHit, RayTraceResult $hitResult) : void {
	}

	protected function onHitBlock(Block $blockHit, RayTraceResult $hitResult) : void {
		parent::onHitBlock($blockHit, $hitResult);
	}

	public function close() : void {
		parent::close();

		$owner = $this->getOwningEntity();

		if ($owner instanceof Player)
			Main::setFishingHook(null, $owner);
	}

	public function handleHookRetraction() : void {
		$this->flagForDespawn();
	}
}

