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

declare(strict_types = 1);

namespace pocketmine\entity\dataproperty\type;

use pocketmine\utils\BinaryStream;

/**
 * @since API 3.0.0
 */
class ShortDataProperty extends DataProperty{

	/** @var int */
	protected $value;

	public function readFrom(BinaryStream $stream){
		$this->setValue($stream->getLShort(true));
	}

	public function writeTo(BinaryStream $stream){
		$stream->putLShort($this->value);
	}

	public function equals(DataProperty $property) : bool{
		return $property instanceof ShortDataProperty and $property->getValue() === $this->value;
	}

	/**
	 * @return int
	 */
	public function getValue(){
		return $this->value;
	}

	public function setValue($value){
		if(is_int($value)){
			$this->value = $value << 48 >> 48; //signed
		}else{
			throw new \InvalidArgumentException("Expected an integer value, got " . gettype($value));
		}
	}
}