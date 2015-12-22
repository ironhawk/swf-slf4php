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
	 */
	public function build($builderContext = null);

	/**
	 * Initialize the builder object from a json object and return the builder
	 *
	 * @param \stdClass $jsonObj
	 * @param array $envVars
	 * @return Builder
	 */
	public function initFromJson($jsonObj, $envVars);

}