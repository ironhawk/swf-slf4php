<?php

namespace cygnus\logging\config\builder;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MonologStreamHandlerBuilder {

	private $stream;

	private $bubble;

	private $level;

	/**
	 *
	 * @return \cygnus\logging\config\builder\MonologStreamHandlerBuilder
	 */
	public static function create() {
		return new MonologStreamHandlerBuilder();
	}

	public function __construct() {
		$this->level = Logger::DEBUG;
		$this->bubble = true;
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
	 * @param int $level        	
	 * @return \cygnus\logging\config\builder\MonologStreamHandlerBuilder
	 */
	public function level($level) {
		$this->level = $level;
		return $this;
	}

	/**
	 *
	 * @param boolean $bubble        	
	 * @return \cygnus\logging\config\builder\MonologStreamHandlerBuilder
	 */
	public function bubble(boolean $bubble) {
		$this->bubble = $bubble;
		return $this;
	}

	
	/**
	 *
	 * @return \Monolog\Handler\StreamHandler
	 */
	public function build() {
		return new StreamHandler($this->stream, $this->level, $this->bubble);
	}

}