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

namespace pocketmine\level\generator;

use pocketmine\level\dimension\Dimension;
use pocketmine\level\format\Chunk;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class LightPopulationTask extends AsyncTask{

	public $levelId;
	public $dimensionId;
	public $chunk;

	public function __construct(Dimension $dimension, Chunk $chunk){
		$this->levelId = $dimension->getLevel()->getId();
		$this->dimensionId = $dimension->getSaveId();
		$this->chunk = $chunk->fastSerialize();
	}

	public function onRun(){
		/** @var Chunk $chunk */
		$chunk = Chunk::fastDeserialize($this->chunk);
		if($chunk === null){
			//TODO error
			return;
		}

		$chunk->recalculateHeightMap();
		$chunk->populateSkyLight();
		$chunk->setLightPopulated();

		$this->chunk = $chunk->fastSerialize();
	}

	public function onCompletion(Server $server){
		$level = $server->getLevel($this->levelId);
		if($level !== null){
			$dimension = $level->getDimension($this->dimensionId);
			if($dimension !== null){
				$chunk = Chunk::fastDeserialize($this->chunk, $level->getProvider());
				if($chunk === null){
					//TODO error
					return;
				}
				$level->generateChunkCallback($chunk->getX(), $chunk->getZ(), $chunk);
			}
		}
	}

}
