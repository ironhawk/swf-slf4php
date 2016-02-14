<?php

namespace swf\lf4php\examples\namespaceA;

use swf\lf4php\LoggerFactory;

class ClassA {

	protected static $_LOG;

	public $name;

	/**
	 *
	 * @return \swf\lf4php\Logger
	 */
	protected static function logger() {
		if (is_null(static::$_LOG))
			static::$_LOG = LoggerFactory::getLogger(static::class);
		return static::$_LOG;
	}

	public function __construct($name = null) {
		$this->name = $name;
		
		static::logger()->info("{} is created", [], $this);
	}

	public function testLog() {
		$salutation = "My Friend";
		$var1 = "value1";
		$var2 = "value2";
		
		static::logger()->debug("hello {}, this is a debug level log dumping some vars: var1={}, var2={}", [], $salutation, $var1, $var2);
		
		try {
			throw new \Exception("A forced exception");
		}
		catch (\Exception $e) {
			static::logger()->error("Ouch {}, we had an exception: {}", [], $salutation, $e);
		}
	}

}