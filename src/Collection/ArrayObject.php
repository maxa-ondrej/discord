<?php

namespace Majksa\Discord\Collection;

use InvalidArgumentException;

class ArrayObject implements Collectable {
	protected array $array = [];
	protected string $type;
	
	public function __construct(string $type) {
		$this->type = $type;
	}
	
	/**
	 * Converts ArrayObject into an array
	 *
	 * @param $value
	 * @return ArrayObject
	 */
	public function add($value): ArrayObject
	{
		if(gettype($value) === 'object') {
			if ($class = get_class($value) !== $this->type) {
				throw new InvalidArgumentException("Value must be an instance of class {$this->type}! Class $class provided.");
			}
		} elseif ($type = get_class($value) !== $this->type) {
			throw new InvalidArgumentException("Value must be of type {$this->type}! Type $type provided.");
		}
		$this->array[] = $value;
		return $this;
	}
    
	/**
	 * Converts ArrayObject into an array
	 *
	 * @return array
	 */
	public function asArray(): array
	{
		$array = [];
		foreach ($this->array as $value) {
			if($value instanceof Collectable) {
				$array[] = $value->asArray();
			} else {
				$array[] = $value;
			}
		}
		return $array;
	}
}
