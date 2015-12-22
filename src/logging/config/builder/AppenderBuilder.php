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
	public abstract function build($builderContext = null);

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \cygnus\logging\config\builder\Builder::initFromJson()
	 * @return \cygnus\logging\config\builder\AppenderBuilder
	 */
	public abstract function initFromJson($jsonObj, $envVars);

}