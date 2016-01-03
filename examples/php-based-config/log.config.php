<?php
use wwwind\logging\config\builder\LogConfigBuilder;
use wwwind\logging\config\builder\monolog\MonologProxyAppenderBuilder;
use wwwind\logging\config\builder\monolog\MonologStreamHandlerBuilder;
use wwwind\logging\config\builder\LoggerBuilder;
use wwwind\logging\LoggerFactory;
use wwwind\logging\config\builder\monolog\MonologLineFormatterBuilder;

// @formatter:off

return LogConfigBuilder::create()->

// adding appenders
appenderBuilder(

	// Monolog based file appenders
	MonologProxyAppenderBuilder::create()->name("MainLogFiles")

		->handlerBuilder(
			MonologStreamHandlerBuilder::create()
			->stream(APP_ROOT_DIR . "/application.log")
			->formatterBuilder(MonologLineFormatterBuilder::create()->format("[%datetime%] %extra.loggerName%.%level_name%: %message% %context% %extra%\n\n"))
		)
		->handlerBuilder(
			MonologStreamHandlerBuilder::create()
			->stream(APP_ROOT_DIR . "/error.log")
			->level(\Monolog\Logger::ERROR)
			->formatterBuilder(MonologLineFormatterBuilder::create()->format("[%datetime%] %extra.loggerName%.%level_name%: %message% %context% %extra%\n\n"))
		)
)

// adding namespace based loggers with different level(s) and routed to appenders
->loggerBuilder(
	LoggerBuilder::create()
	->name("wwwind.phpbasic")
	->level(LoggerFactory::INFO)
	->appenderName("MainLogFiles")
)
->loggerBuilder(
	LoggerBuilder::create()
	->level(LoggerFactory::DEBUG)
	->appenderName("MainLogFiles")
)

// and now build it into a config!
->build();
