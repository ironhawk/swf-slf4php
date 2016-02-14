<?php

namespace wwwind\logging\config\builder\monolog;

use wwwind\logging\config\builder\Builder;
use Monolog\Formatter\FormatterInterface;

abstract class MonologFormatterBuilder implements Builder {

	/**
	 * This returns \Monolog\Formatter\XXXFormatter<p>
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\Builder::build()
	 * @return \Monolog\Formatter\FormatterInterface
	 */
	public abstract function build();

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