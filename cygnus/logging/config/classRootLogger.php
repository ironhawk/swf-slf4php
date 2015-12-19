<?php

namespace cygnus\phpbasic\lib\logging\config;

class RootLogger extends Logger {

	public function __construct($level, $appenders) {
		parent::__construct("root", $level, $appenders);
	}

}