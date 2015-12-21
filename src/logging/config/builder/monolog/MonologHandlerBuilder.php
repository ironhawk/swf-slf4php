<?php

namespace cygnus\logging\config\builder\monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

abstract class MonologHandlerBuilder {

	private $formatterBuilder;

	private $bubble = true;

	private $level = Logger::DEBUG;

	/**
	 *
	 * @param MonologFormatterBuilder $builder        	
	 * @return \cygnus\logging\config\builder\MonologStreamHandlerBuilder
	 */
	public function formatter(MonologFormatterBuilder $builder) {
		$this->formatterBuilder = $builder;
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
	public function bubble($bubble) {
		$this->bubble = $bubble;
		return $this;
	}

	protected function injectSetup(AbstractProcessingHandler $handler) {
		$handler->setBubble($this->bubble);
		$handler->setLevel($this->level);
		if (! is_null($this->formatterBuilder)) {
			$handler->setFormatter($this->formatterBuilder->build());
		}
	}

}