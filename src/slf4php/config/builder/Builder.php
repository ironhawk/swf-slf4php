<?php

namespace swf\slf4php\config\builder;

/**
 * Interface definition for builders we use to build up configuration
 *
 * @author ironhawk
 *        
 */
interface Builder {

	/**
	 * You just need to create and return a new instance of your builder class<p>
	 * This is a Helper method for native PHP based config building - see example: php-based-config !
	 *
	 * @return Builder
	 */
	public static function create();

	/**
	 * Build and return the appropriate object instance
	 */
	public function build();

	/**
	 * Initialize the builder object from a json object and return the builder
	 *
	 * @param \stdClass $jsonObj        	
	 * @param array $envVars        	
	 * @return Builder
	 */
	public function initFromJson($jsonObj, $envVars);

}