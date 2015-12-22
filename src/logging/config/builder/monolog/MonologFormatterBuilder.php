<?php

namespace cygnus\logging\config\builder\monolog;

use cygnus\logging\config\builder\Builder;

abstract class MonologFormatterBuilder implements Builder {

	/**
	 * This returns \Monolog\Formatter\XXXFormatter
	 */
	public abstract function build();

	/**
	 * This returns \Monolog\Formatter\XXXFormatter
	 */
	public abstract function buildFromJson($jsonObj, $envVars);

}