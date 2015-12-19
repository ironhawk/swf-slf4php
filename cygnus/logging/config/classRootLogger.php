<?php

namespace cygnus\logging\config;

class RootLogger extends Logger {

	public function __construct($level, $appenders) {
		parent::__construct("root", $level, $appenders);
	}

}