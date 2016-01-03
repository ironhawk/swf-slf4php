<?php

namespace wwwind\logging\config\builder;

abstract class AppenderBuilder implements Builder {

	protected $name;

	/**
	 *
	 * @return \wwwind\logging\config\builder\AppenderBuilder
	 */
	public function name($name) {
		$this->name = $name;
		return $this;
	}

	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\Builder::build()
	 * @return \wwwind\logging\Appender
	 */
	public abstract function build(array $builderContext = null);

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\Builder::initFromJson()
	 * @return \wwwind\logging\config\builder\AppenderBuilder
	 */
	public abstract function initFromJson($jsonObj, $envVars);

}