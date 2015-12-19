<?php

namespace cygnus\logging\config\builder;

use cygnus\logging\config\MonologProxyAppender;

class MonologProxyAppenderBuilder extends AppenderBuilder {

	private $handlerBuilders;

	/**
	 *
	 * @return \cygnus\logging\config\builder\MonologProxyAppenderBuilder
	 */
	public static function create() {
		return new MonologProxyAppenderBuilder();
	}

	public function __construct() {
		$this->handlerBuilders = [];
	}

	
	/**
	 *
	 * @return \cygnus\logging\config\builder\MonologProxyAppenderBuilder
	 */
	public function name($name) {
		parent::name($name);
		return $this;
	}

	
	/**
	 *
	 * @return \cygnus\logging\config\builder\MonologProxyAppenderBuilder
	 */
	public function handler($handlerBuilder) {
		$this->handlerBuilders[] = $handlerBuilder;
		return $this;
	}

	/**
	 *
	 * @return \cygnus\logging\config\MonologProxyAppender
	 */
	public function build() {
		$handlers = [];
		foreach ($this->handlerBuilders as $handlerBuilder) {
			$handlers[] = $handlerBuilder->build();
		}
		$appender = new MonologProxyAppender($this->name, $handlers);
		return $appender;
	}

}