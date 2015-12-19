<?php

namespace cygnus\phpbasic\lib\logging\config\builder;

use cygnus\phpbasic\lib\logging\Appender;

abstract class AppenderBuilder {

	protected $name;

	/**
	 *
	 * @return \cygnus\phpbasic\lib\logging\config\builder\AppenderBuilder
	 */
	public function name($name) {
		$this->name = $name;
		return $this;
	}

	
	/**
	 *
	 * @return \cygnus\phpbasic\lib\logging\config\builder\Appender
	 */
	public abstract function build();

}