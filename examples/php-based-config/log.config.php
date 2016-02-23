<?php
use swf\slf4php\config\builder\LogConfigBuilder;
use swf\slf4php\config\builder\monolog\MonologProxyAppenderBuilder;
use swf\slf4php\config\builder\monolog\MonologStreamHandlerBuilder;
use swf\slf4php\config\builder\LoggerBuilder;
use swf\slf4php\LoggerFactory;
use swf\slf4php\config\builder\monolog\MonologLineFormatterBuilder;
use swf\slf4php\config\builder\LoggerTemplateBuilder;

// @formatter:off

return LogConfigBuilder::create()->

// adding appenders
appender(

	// Monolog based file appenders
	MonologProxyAppenderBuilder::create()->name("MainLogFiles")

		->handler(
			MonologStreamHandlerBuilder::create()
			->stream(LOG_DIR . "/application.log")
			->formatter(MonologLineFormatterBuilder::create()->format("[%datetime%] %extra.loggerName%.%level_name%: %message% %context% %extra%\n\n")->build())->build()
		)
		->handler(
			MonologStreamHandlerBuilder::create()
			->stream(LOG_DIR . "/error.log")
			->level(\Monolog\Logger::ERROR)
			->formatter(MonologLineFormatterBuilder::create()->format("[%datetime%] %extra.loggerName%.%level_name%: %message% %context% %extra%\n\n")->build())->build()
		)->build()
)

// adding namespace based loggers with different level(s) and routed to appenders
->logger(
	LoggerTemplateBuilder::create()
	->name("swf.logging.examples.namespaceA")
	->level(LoggerFactory::DEBUG)
	->appenderName("MainLogFiles")
	->build()
)
->logger(
	LoggerTemplateBuilder::create()
	->name("swf.logging.examples.namespaceB")
	->level(LoggerFactory::ERROR)
	->appenderName("MainLogFiles")
	->build()
)
->logger(
	LoggerTemplateBuilder::create()
	->level(LoggerFactory::INFO)
	->appenderName("MainLogFiles")
	->build()
)

// and now build it into a config!
->build();
