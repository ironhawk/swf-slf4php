<?php

namespace cygnus\logging\config\builder;

use cygnus\logging\Appender;

abstract class AppenderBuilder {

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
	 * @return \cygnus\logging\config\builder\Appender
	 */
	public abstract function build();

}