<?php

namespace cygnus\logging\config\builder;

abstract class AppenderBuilder implements Builder {

	protected $name;

	/**
	 *
	 * @return \cygnus\logging\config\builder\AppenderBuilder
	 */
	public function name($name) {
		$this->name = $name;
		return $this;
	}

	
	/**
	 *
	 * @return \cygnus\logging\Appender
	 */
	public abstract function build();

	/**
	 *
	 * @return \cygnus\logging\Appender
	 */
	public abstract function buildFromJson($jsonObj, $envVars);

}