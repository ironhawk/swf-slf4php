<?php

namespace cygnus\logging\config\builder;

/**
 * Interface definition for builders we use to build up configuration
 *
 * @author ironhawk
 *
 */
interface Builder {

	/**
	 * Build and return the appropriate object instance
	 *
	 * @param array $builderContext
	 *        	To pass around necessary info for building objects. (At the moment it is not really used except one
	 *        	case which is the LoggerBuilder)
	 */
	public function build(array $builderContext = null);

	/**
	 * Initialize the builder object from a json object and return the builder
	 *
	 * @param \stdClass $jsonObj
	 * @param array $envVars
	 * @return Builder
	 */
	public function initFromJson($jsonObj, $envVars);

}