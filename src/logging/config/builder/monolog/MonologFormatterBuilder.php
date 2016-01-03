<?php

namespace wwwind\logging\config\builder\monolog;

use wwwind\logging\config\builder\Builder;

abstract class MonologFormatterBuilder implements Builder {

	/**
	 * This returns \Monolog\Formatter\XXXFormatter<p>
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\Builder::build()
	 */
	public abstract function build(array $builderContext = null);

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\Builder::initFromJson()
	 * @return \wwwind\logging\config\builder\MonologFormatterBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		// we have nothing to do here - yet
		return $this;
	}

}