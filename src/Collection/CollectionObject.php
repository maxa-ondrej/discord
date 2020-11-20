<?php

namespace Majksa\Discord\Collection;

class CollectionObject implements Collectable {
	/**
	 * Converts CollectionObject into an array
	 *
	 * @return array
	 */
	public function asArray(): array
	{
		$array = [];
		foreach ($this as $camelKey => $value) {
			if (isset($this->{$camelKey})) {
				$key = $this->camelToPascal($camelKey);
				if($value instanceof Collectable) {
					$array[$key] = $value->asArray();
				} else {
					$array[$key] = $value;
				}
			}
		}
		return $array;
	}

    /**
     * Converts CamelCase to pascal_case
     *
     * @param [type] $camelCase
     * @return void
     */
	protected function camelToPascal($camelCase) {
		preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $camelCase, $matches);
		$ret = $matches[0];
		foreach ($ret as &$match) {
			$match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
		}
		return implode('_', $ret);
	}
}
