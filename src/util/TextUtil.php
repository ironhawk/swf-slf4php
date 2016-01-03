<?php

namespace wwwind\util;

class TextUtil {

	const EMPTY_STRING = "";

	const VARIABLE_PLACEHOLDER = "{}";

	private function __construct() {}

	/**
	 * The given string might contain placeholders "{}" which will be replaced by
	 * string representation of data provided in the given array<p>
	 *
	 * Number of placeholders in the string should match with the number of elements provided in the
	 * data array<p>
	 *
	 * Example:<br/>
	 * resolveStringWithDataArray("Hello {}! Sorry but we found an error: {}", ["User", "File not found"])<br/>
	 * will output:<br/>
	 * "Hello User! Sorry but we found an error: File not found"<p>
	 *
	 * note: If you pass in arrays or objects as variable values they will be turned into their string representation
	 * using the varToString() method.
	 *
	 * @param string $msg
	 *        	with placeholders VARIABLE_PLACEHOLDER ("{}" by default)
	 * @param array $data
	 *        	the data
	 * @return string
	 */
	public static function resolveStringWithDataArray($msg, array $data) {
		if (is_null($msg) || strlen(trim($msg)) == 0 || empty($data))
			return $msg;
		$replaced = [];
		$pieces = explode(self::VARIABLE_PLACEHOLDER, $msg, count($data) + 1);
		$data[] = self::EMPTY_STRING;
		for ($idx = 0; $idx < count($pieces); $idx ++) {
			$replaced[] = $pieces[$idx];
			$replaced[] = static::varToString($data[$idx]);
		}
		return implode("", $replaced);
	}

	
	/**
	 * Resolves variables in the given text using the given valueSet.<p>In case a variable found which we do not
	 * have value for then that variable will not be substituted but will stay in the text<p>
	 *
	 * note: If you pass in arrays or objects as variable values they will be turned into their string representation
	 * using the varToString() method.
	 *
	 * @param string $textToParse
	 *        	the text to parse
	 * @param array $valueSet
	 *        	variables and their values
	 * @param string $variableBegin
	 *        	begin marker of a variable
	 * @param string $variableEnd
	 *        	end marker of a variable
	 * @return string the resolved text
	 */
	public static function resolveStringWithAssocArray($textToParse, array $valueSet, $variableBegin = "\${", $variableEnd = "}") {
		if (is_null($valueSet) || is_null($textToParse) || strlen($textToParse) == 0)
			return $textToParse;
		
		$previndex = 0;
		
		$sb = [];
		$index = strpos($textToParse, $variableBegin);
		while ($index !== false) {
			$sb[] = substr($textToParse, $previndex, $index - $previndex);
			
			// find the var end
			$endindex = strpos($textToParse, $variableEnd, $index + strlen($variableBegin));
			if ($endindex === false) {
				// mmm we have reached the end of the text - variable end marker not found
				$endindex = strlen($textToParse);
			}
			
			$helper = $index + strlen($variableBegin);
			$valueId = substr($textToParse, $helper, $endindex - $helper);
			$vobj = null;
			if (array_key_exists($valueId, $valueSet)) {
				$vobj = static::varToString($valueSet[$valueId]);
				$sb[] = static::varToString($vobj);
			} else {
				$sb[] = $variableBegin;
				$sb[] = $valueId;
				$sb[] = $variableEnd;
			}
			
			$previndex = $endindex + strlen($variableEnd);
			$index = strpos($textToParse, $variableBegin, $previndex);
		}
		
		if ($previndex < strlen($textToParse))
			$sb[] = substr($textToParse, $previndex);
		
		return implode(static::EMPTY_STRING, $sb);
	}

	/**
	 * Returns the text representation of the given variable<p>
	 *
	 * If this is an array then it is printed out using print_r() method. If this is an object
	 * which has a __toString() function defined then that will be used - if there is no __toString()
	 * then print_r() is used again. For NULL values result is "NULL" string. For other primitive
	 * values it is their readable natural value.
	 *
	 * @param mixed $var        	
	 * @return string
	 */
	protected static function varToString($var) {
		if (is_null($var))
			return "NULL";
		if (is_array($var)) {
			return print_r($var, true);
		}
		if (is_object($var) && ! method_exists($var, "__toString")) {
			return print_r($var, true);
		}
		return $var;
	}

}