<?php

namespace swf\util;

use swf\errors\Preconditions;
use swf\errors\IllegalStateException;

/**
 * Set of static helper methods to handle Json input
 *
 * @author ironhawk
 *        
 */
class JsonUtil {

	private function __construct() {}

	/**
	 * Takes a file path and returns parsed json object(s) result
	 *
	 * @param string $jsonFilePath        	
	 * @return \stdClass the file content parsed into objects
	 * @throws IllegalStateException in case parsing fails for any reason
	 */
	public static function getJsonObjects($jsonFilePath) {
		$jsonString = file_get_contents($jsonFilePath);
		Preconditions::checkState($jsonString !== FALSE, "failed to read file '{}'", $jsonFilePath);
		$jsonObj = json_decode($jsonString);
		Preconditions::checkState(! is_null($jsonObj), "failed to json decode file '{}'!", $jsonFilePath);
		return $jsonObj;
	}

	/**
	 * Our json parsing supports using variables in string values which are resolved.
	 * Variables can be refrenced in format ${varName} in strings
	 * Resolving variables will be done using the given vars array
	 *
	 * @param string $jsonRawStrValue        	
	 * @param array $envVars
	 *        	the variables in varName => varValue format
	 * @return string the resolved string
	 */
	public static function getResolvedJsonStringValue($jsonRawStrValue, array $envVars) {
		return TextUtil::resolveStringWithAssocArray($jsonRawStrValue, $envVars);
	}

	/**
	 * Converts the json value to boolean value
	 *
	 * @param string $jsonValue        	
	 * @param array $envVars
	 *        	the variables in varName => varValue format
	 * @return boolean
	 */
	public static function getAsBoolValue($jsonValue, array $envVars) {
		if (is_bool($jsonValue))
			return $jsonValue;
		if (is_numeric($jsonValue))
			return ($jsonValue != 0);
			// it should be string!
		$jsonValue = static::getResolvedJsonStringValue($jsonValue, $envVars);
		return ($jsonStrValue == '1' || strcasecmp($jsonStrValue, "true") || strcasecmp($jsonStrValue, "on"));
	}

}