<?php

namespace cygnus\test;

use cygnus\logging\LoggerFactory;
use cygnus\logging\Logger;
use cygnus\logging\config\builder\LogConfigBuilder;
use cygnus\util\JsonUtil;
if (! defined('APP_ROOT_DIR'))
	define('APP_ROOT_DIR', str_replace('\\', '/', realpath("../..")));

require_once APP_ROOT_DIR . '/vendor/autoload.php';

$loggerConfig = LogConfigBuilder::create()->initFromJson(JsonUtil::getJsonObjects("log.config.json"), [
	'APP_ROOT_DIR' => APP_ROOT_DIR
])->build();
LoggerFactory::init($loggerConfig);

class TestClass {

	private static $_LOG;

	/**
	 *
	 * @return Logger
	 */
	private static function logger() {
		if (is_null(static::$_LOG))
			static::$_LOG = LoggerFactory::getLogger(self::class);
		return static::$_LOG;
	}

	public function __construct() {
		self::logger()->info("TestClass created");
	}

	public function testLog() {
		$var1 = "Attila";
		$var2 = "egyet";
		
		self::logger()->debug("hello {} tesztelünk {} na", [], $var1, $var2);
		
		self::logger()->error("műsodszor is hello {} tesztelünk {} na", [], $var1, $var2);
	}

}

$qqq = new TestClass();
$qqq->testLog();

