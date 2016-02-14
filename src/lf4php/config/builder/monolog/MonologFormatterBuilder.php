<?php

namespace swf\lf4php\config\builder\monolog;

use swf\lf4php\config\builder\Builder;
use Monolog\Formatter\FormatterInterface;

abstract class MonologFormatterBuilder implements Builder {

	/**
	 * This returns \Monolog\Formatter\XXXFormatter<p>
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\lf4php\config\builder\Builder::build()
	 * @return \Monolog\Formatter\FormatterInterface
	 */
	public abstract function build();

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\lf4php\config\builder\Builder::initFromJson()
	 * @return \swf\lf4php\config\builder\MonologFormatterBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		// we have nothing to do here - yet
		return $this;
	}

}