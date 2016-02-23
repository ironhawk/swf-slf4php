<?php

namespace swf\slf4php\config\builder\monolog;

use swf\slf4php\config\builder\Builder;
use Monolog\Formatter\FormatterInterface;

abstract class MonologFormatterBuilder implements Builder {

	/**
	 * This returns \Monolog\Formatter\XXXFormatter<p>
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\slf4php\config\builder\Builder::build()
	 * @return \Monolog\Formatter\FormatterInterface
	 */
	public abstract function build();

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\slf4php\config\builder\Builder::initFromJson()
	 * @return \swf\slf4php\config\builder\MonologFormatterBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		// we have nothing to do here - yet
		return $this;
	}

}