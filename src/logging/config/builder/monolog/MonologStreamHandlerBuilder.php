<?php

namespace cygnus\logging\config\builder\monolog;

use Monolog\Handler\StreamHandler;

class MonologStreamHandlerBuilder extends MonologHandlerBuilder {

	private $stream;

	/**
	 *
	 * @return \cygnus\logging\config\builder\MonologStreamHandlerBuilder
	 */
	public static function create() {
		return new MonologStreamHandlerBuilder();
	}

	/**
	 *
	 * @param string $stream        	
	 * @return \cygnus\logging\config\builder\MonologStreamHandlerBuilder
	 */
	public function stream($stream) {
		$this->stream = $stream;
		return $this;
	}

	
	/**
	 *
	 * @return \Monolog\Handler\StreamHandler
	 */
	public function build() {
		$handler = new StreamHandler($this->stream);
		parent::injectSetup($handler);
		return $handler;
	}

}