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

namespace pocketmine\level;

use pocketmine\level\dimension\Dimension;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\level\utils\LevelException;

class WeakPosition extends Position{

	protected $levelId = -1;
	protected $dimensionId = null;

	/**
	 * @param int|float      $x
	 * @param int|float      $y
	 * @param int|float      $z
	 * @param Dimension|null $dimension
	 */
	public function __construct($x = 0, $y = 0, $z = 0, Dimension $dimension = null){
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		$this->levelId = ($dimension !== null ? $dimension->getLevel()->getId() : -1);
		$this->dimensionId =  $dimension !== null ? $dimension->getSaveId() : null;
	}

	public static function fromObject(Vector3 $pos, Dimension $dimension = null){
		return new WeakPosition($pos->x, $pos->y, $pos->z, $dimension);
	}

	public function getDimension(){
		if(($level = $this->getLevel()) !== null){
			return $level->getDimension($this->dimensionId);
		}

		return null;
	}

	public function setDimension(Dimension $dimension = null){
		if($dimension !== null){
			if($dimension->isGarbage()){
				throw new \InvalidArgumentException("Specified dimension has been unloaded and cannot be used");
			}

			$this->dimensionId = $dimension->getSaveId();
			$this->levelId = $dimension->getLevel()->getId();
		}else{
			$this->dimensionId = null;
			$this->levelId = -1;
		}
	}

	/**
	 * @return Level|null
	 */
	public function getLevel(){
		return Server::getInstance()->getLevel($this->levelId);
	}

	/**
	 * Returns a side Vector
	 *
	 * @param int $side
	 * @param int $step
	 *
	 * @return WeakPosition
	 *
	 * @throws LevelException
	 */
	public function getSide($side, $step = 1){
		assert($this->isValid());

		return WeakPosition::fromObject(parent::getSide($side, $step), $this->getDimension());
	}

	public function __toString(){
		return "Weak" . parent::__toString();
	}
}