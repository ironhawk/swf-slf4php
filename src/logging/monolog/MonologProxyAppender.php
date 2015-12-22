<?php

namespace cygnus\logging\monolog;

use cygnus\logging\Appender;
use Monolog\Logger;

/**
 * This class proxies Appender functionality into Monolog so we can use all features provided by Monolog
 *
 * @author ironhawk
 *        
 */
class MonologProxyAppender extends Appender {

	/**
	 *
	 * @var Logger
	 */
	private $monologLogger;

	public function __construct($name, $handlers = []) {
		parent::__construct($name);
		$this->handlers = $handlers;
		
		$this->monologLogger = new Logger($name);
		foreach ($handlers as $handler) {
			$this->monologLogger->pushHandler($handler);
		}
		$this->monologLogger->pushProcessor(MonologInjectorProcessor::getInstance());
	}

	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::emergency()
	 */
	public function emergency($message, array $context = array()) {
		$this->monologLogger->emerg($message, $context);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::alert()
	 */
	public function alert($message, array $context = array()) {
		$this->monologLogger->alert($message, $context);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::critical()
	 */
	public function critical($message, array $context = array()) {
		$this->monologLogger->crit($message, $context);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::error()
	 */
	public function error($message, array $context = array()) {
		$this->monologLogger->err($message, $context);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::warning()
	 */
	public function warning($message, array $context = array()) {
		$this->monologLogger->warn($message, $context);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::notice()
	 */
	public function notice($message, array $context = array()) {
		$this->monologLogger->notice($message, $context);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::info()
	 */
	public function info($message, array $context = array()) {
		$this->monologLogger->info($message, $context);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::debug()
	 */
	public function debug($message, array $context = array()) {
		$this->monologLogger->debug($message, $context);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::log()
	 */
	public function log($level, $message, array $context = array()) {
		$this->monologLogger->addRecord($level, $message, $context);
	}

}