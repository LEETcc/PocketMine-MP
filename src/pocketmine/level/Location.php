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

class Location extends Position{

	public $yaw;
	public $pitch;

	/**
	 * @param int|float $x
	 * @param int|float $y
	 * @param int|float $z
	 * @param float     $yaw
	 * @param float     $pitch
	 * @param Dimension $dimension
	 */
	public function __construct($x = 0, $y = 0, $z = 0, $yaw = 0.0, $pitch = 0.0, Dimension $dimension = null){
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		$this->yaw = $yaw;
		$this->pitch = $pitch;
		$this->dimension = $dimension;
	}

	/**
	 * @param Vector3        $pos
	 * @param Dimension|null $dimension
	 * @param float          $yaw   default 0.0
	 * @param float          $pitch default 0.0
	 *
	 * @return Location
	 */
	public static function fromObject(Vector3 $pos, Dimension $dimension = null, $yaw = 0.0, $pitch = 0.0){
		return new Location($pos->x, $pos->y, $pos->z, $yaw, $pitch, $dimension ?? (($pos instanceof Position) ? $pos->getDimension() : null));
	}

	public function getYaw(){
		return $this->yaw;
	}

	public function getPitch(){
		return $this->pitch;
	}

	public function __toString(){
		return "Position(" .
			($this->isValid() ? "level=" . $this->dimension->getLevel()->getName() . ",dimension=" . $this->dimension->getSaveId()
				: "level=null,dimension=null") .
			",x=" . $this->x . ",y=" . $this->y . ",z=" . $this->z . ",yaw=" . $this->yaw . ",pitch=" . $this->pitch . ")";
	}
}
