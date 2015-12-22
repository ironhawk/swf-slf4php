<?php

namespace cygnus\logging\monolog;

/**
 * This simple processor is created for Monolog based Appenders.<p>
 * Logger adds its name to the context array under key 'loggerName'.
 * This Processor simply takes this if present
 * and moves this into the 'extra' part of the record so you can use it in Monolog Formatters<p>
 * This class follows the Singleton pattern as this is a stateless instance so we do not need more than one.
 *
 * @author ironhawk
 *        
 */
class MonologInjectorProcessor {

	private static $instance;

	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new MonologInjectorProcessor();
		}
		return self::$instance;
	}

	private function __construct() {}

	/**
	 *
	 * @param array $record        	
	 * @return array
	 */
	public function __invoke(array $record) {
		if (isset($record['context']['loggerName'])) {
			$record['extra']['loggerName'] = $record['context']['loggerName'];
			unset($record['context']['loggerName']);
		}
		return $record;
	}

}