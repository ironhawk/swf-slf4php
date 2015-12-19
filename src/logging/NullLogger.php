<?php

namespace cygnus\logging;

use Psr\Log\LoggerInterface;

/**
 * All log messages will be disposed.
 * This class follows the Singleton pattern as there is no point to have more
 * instances for this one
 *
 * @author ironhawk
 *        
 */
class NullLogger implements LoggerInterface {

	private static $instance;

	public static function getInstance() {
		if (is_null(static::$instance)) {
			static::$instance = new NullLogger();
		}
		return static::$instance;
	}

	private function __construct() {}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::emergency()
	 */
	public function emergency($message, array $context = array()) {}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::alert()
	 */
	public function alert($message, array $context = array()) {}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::critical()
	 */
	public function critical($message, array $context = array()) {}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::error()
	 */
	public function error($message, array $context = array()) {}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::warning()
	 */
	public function warning($message, array $context = array()) {}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::notice()
	 */
	public function notice($message, array $context = array()) {}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::info()
	 */
	public function info($message, array $context = array()) {}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::debug()
	 */
	public function debug($message, array $context = array()) {}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Psr\Log\LoggerInterface::log()
	 */
	public function log($level, $message, array $context = array()) {}

}