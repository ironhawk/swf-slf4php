<?php

namespace wwwind\errors;

use wwwind\util\TextUtil;

/**
 * Handy helper class to quickly check the situation and start throwing exceptions<p>
 * This class was inspired by google.preconditions solution
 *
 * @author ironhawk
 *        
 */
class Preconditions {

	private function __construct() {}

	private static function parseMessage(array $funcParams) {
		array_shift($funcParams);
		$msg = array_shift($funcParams);
		return TextUtil::resolveStringWithDataArray($msg, $funcParams);
	}

	
	/**
	 * Checks the argument if that satisfies the given expression - in case not then an InvalidArgumentException is
	 * thrown with the error message you have provided.<p>
	 *
	 * The given error message might contain placeholders "{}" which will be replaced by
	 * string representation of data provided in the given array
	 *
	 * @param boolean $expression
	 *        	An expression which evaluates to boolean
	 * @param string $errorMessage
	 *        	Exception message which is thrown if expression is not true
	 * @param $_ optional
	 *        	data which is used to substitute placeholders in error message if the Exception needs to be
	 *        	thrown
	 * @throws InvalidArgumentException
	 */
	public static function checkArgument($expression, $errorMessage = "") {
		if (! $expression) {
			throw new \InvalidArgumentException(self::parseMessage(func_get_args()));
		}
	}

	
	/**
	 * Checks the state if that satisfies the given expression - in case not then an IllegalStateException is
	 * thrown with the error message you have provided.<p>
	 *
	 * The given error message might contain placeholders "{}" which will be replaced by
	 * string representation of data provided in the given array
	 *
	 * @param boolean $expression
	 *        	An expression which evaluates to boolean
	 * @param string $errorMessage
	 *        	Exception message which is thrown if expression is not true
	 * @param $_ optional
	 *        	data which is used to substitute placeholders in error message if the Exception needs to be
	 *        	thrown
	 * @throws IllegalStateException
	 */
	public static function checkState($expression, $errorMessage = "") {
		if (! $expression) {
			throw new IllegalStateException(self::parseMessage(func_get_args()));
		}
	}

}