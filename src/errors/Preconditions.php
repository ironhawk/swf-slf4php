<?php

namespace cygnus\errors;

/**
 * Handy helper class to quickly check the situation and start throwing exceptions<p>
 * This class was inspired by google.preconditions solution
 *
 * @author ironhawk
 *        
 */
class Preconditions {

	/**
	 * Checks the argument if that satisfies the given expression
	 *
	 * @param unknown $expression        	
	 * @param string $errorMessage        	
	 * @throws InvalidArgumentException
	 */
	public static function checkArgument($expression, $errorMessage = "") {
		if (! $expression) {
			throw new InvalidArgumentException($errorMessage);
		}
	}

}