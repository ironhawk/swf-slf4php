<?php

namespace swf\testing;

/**
 * Some helper utility methods used in BDD Steps (officially called XXXContext) files
 *
 * @author ironhawk
 *        
 */
class BDDUtil {

	/**
	 * Returns a string from given column of TableNode row.
	 * If value is empty or column doesn't exist then NULL returned. There are also
	 * special values:<ul>
	 * <li>"!empty" - returns an empty string
	 * </ul>
	 *
	 * @param array $row
	 *        	row of table got using the getColumnsHash() method
	 * @param string $columnName
	 *        	from which column?
	 * @return string this method always returns a string - even if the object was not string...
	 */
	public static function getStringFromColumn(array $row, $columnName) {
		if (! isset($row[$columnName]))
			return null;
		$value = trim($row[$columnName]);
		if (! is_string($value))
			// take the string representation of the value
			$value = "" + $value;
		if (strcasecmp($value, "!empty") == 0)
			return "";
		return $value;
	}

	
	/**
	 * Splits up content of given string used given separator then returns a list.
	 * Elements are trimmed!
	 * If value is NULL or empty then an empty list is returned
	 *
	 * @param string $listStr;
	 *        	the string
	 * @return array list of elements found in string
	 */
	public static function getListFromString($listStr, $separator = ',') {
		if (is_null($listStr))
			return [];
		$list = explode($separator, $listStr);
		$list = static::arrayTrim($list);
		return $list;
	}

	/**
	 * Splits up content of given column used given separator then returns a list.
	 * If column does not exist
	 * or value is empty then an empty list is returned
	 *
	 * @param array $row
	 *        	row of table got using the getColumnsHash() method
	 * @param string $columnName
	 *        	from which column?
	 * @return array list of elements found in row as string representation
	 */
	public static function getListFromColumn(array $row, $columnName, $separator = ',') {
		$value = static::getStringFromColumn($row, $columnName);
		if (is_null($value))
			return [];
		return static::getListFromString($value, $separator);
	}

	/**
	 * Iterates through an array and trims each element.
	 * Result is returned in a new array, original array
	 * is untouched
	 *
	 * @param array $array
	 *        	the array
	 * @param boolean $removeEmptyElements
	 *        	if true then (became) empty elements are not added
	 */
	public static function arrayTrim($array, $removeEmptyElements = false) {
		if (empty($array))
			return $array();
		
		$arr = array();
		foreach ($array as $element) {
			$element = trim($element);
			if (! empty($element) || ! $removeEmptyElements)
				array_push($arr, $element);
		}
		
		return $arr;
	}

	/**
	 * Concatenates arrays given as parameters
	 */
	public static function arrayConcatenate() {
		$arrays = func_get_args();
		$result = array();
		if (! empty($arrays)) {
			foreach ($arrays as $array) {
				if (is_array($array)) {
					if (empty($result)) {
						$result = $array;
					} else {
						foreach ($array as $element) {
							$result[] = $element;
						}
					}
				}
			}
		}
		
		return $result;
	}

}