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
use pocketmine\level\utils\LevelException;
use pocketmine\math\Vector3;
use pocketmine\utils\MainLogger;

class Position extends Vector3{

	/** @var Dimension */
	public $dimension = null; //TODO: remove public usages of this

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
		$this->setDimension($dimension);
	}

	public static function fromObject(Vector3 $pos, Dimension $dimension = null){
		return new Position($pos->x, $pos->y, $pos->z, $dimension);
	}

	/**
	 * Returns the target Dimension, or null if the target is not valid.
	 * If a reference exists to a Dimension which is closed or has an invalid parent, the reference will be destroyed and null will be returned.
	 *
	 * @return Dimension|null
	 */
	public function getDimension(){
		if($this->dimension !== null and $this->dimension->isGarbage()){
			MainLogger::getLogger()->debug("Position was holding a reference to a garbage Dimension");
			$this->dimension = null;
		}

		return $this->dimension;
	}

	/**
	 * Sets the target Dimension of this Position
	 *
	 * @param Dimension|null $dimension
	 *
	 * @throws \InvalidArgumentException if the specified target is not usable
	 */
	public function setDimension(Dimension $dimension = null){
		if($dimension !== null and $dimension->isGarbage()){
			throw new \InvalidArgumentException("Specified dimension has been unloaded and cannot be used");
		}

		$this->dimension = $dimension;
	}

	/**
	 * Returns the parent Level of the target Dimension, or null if the target is not valid.
	 *
	 * @return Level|null
	 */
	public function getLevel(){
		if($this->isValid()){
			return $this->dimension->getLevel();
		}

		return null;
	}

	/**
	 * Returns whether this position has a valid target Dimension
	 *
	 * @return bool
	 */
	public function isValid(){
		return $this->getDimension() instanceof Dimension;
	}

	/**
	 * Returns a side Vector
	 *
	 * @param int $side
	 * @param int $step
	 *
	 * @return Position
	 *
	 * @throws LevelException
	 */
	public function getSide($side, $step = 1){
		return Position::fromObject(parent::getSide($side, $step), $this->getDimension());
	}

	public function __toString(){
		return "Position(" .
			($this->isValid() ? "level=" . $this->dimension->getLevel()->getName() . ",dimension=" . $this->dimension->getSaveId()
				: "level=null,dimension=null") .
			",x=" . $this->x . ",y=" . $this->y . ",z=" . $this->z . ")";
	}

	/**
	 * @param int|float $x
	 * @param int|float $y
	 * @param int|float $z
	 *
	 * @return Position
	 */
	public function setComponents($x, $y, $z) : Position{
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		return $this;
	}

}
