<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\block\Solid;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;

class FlintSteel extends Tool {

	public function __construct($meta = 0, $count = 1) {
		parent::__construct(self::FLINT_STEEL, $meta, $count, "Flint and Steel");
	}

	public function canBeActivated() : bool {
		return false;
	}

	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz) {
		if($block->getId() === self::AIR and ($target instanceof Solid)) {
			$level->setBlock($block, new Fire(), true);

			/** @var Fire $block */
			$block = $level->getBlock($block);
			if($block->getSide(Vector3::SIDE_DOWN)->isTopFacingSurfaceSolid() or $block->canNeighborBurn()) {
				$level->scheduleUpdate($block, $block->getTickRate() + mt_rand(0, 10));
				//	return true;
			}

			if($player->isSurvival()) {
				$this->useOn($block, 2);//耐久跟报废分别写在 tool 跟 level 了
				$player->getInventory()->setItemInHand($this);
			}

			return true;
		}

		return false;
	}
}