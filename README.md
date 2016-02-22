# Quick overview


# Features

   * implements psr/log interface
   * we have Loggers and Appenders (output channel) - a Logger can be configured to use multiple Appenders at the same time
   * you can create your own Appenders easily by implementing the interface
   * framework has `MonologProxyAppender` out of the box so you can use [Monolog](https://github.com/Seldaek/monolog) as an implementation right now
   * you can configure your Loggers on a namespace (aka package) basis which allows you to use different log levels / output channels (Appenders) at different packages (just like we regularly do in Java!)
   * if you are not configuring up logging then `LoggerFactory` will default to returning 'no operation' Loggers - you can configure logging later anytime
   * builder based architecture - for building up configuration easily
   * all config builder classes support JSON format so you can define your config in JSON files quickly


```php
<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('name');
$log->pushHandler(new StreamHandler('path/to/your.log', Logger::WARNING));

// add records to the log
$log->addWarning('Foo');
$log->addError('Bar');
```
   
Take a quick look on the following JSON config and you will immediatelly understand the concept:


```json
{

	"appenders" : [
	
		{
			"name" : "MainLogFiles",
			"builderClass" : "swf\\lf4php\\config\\builder\\monolog\\MonologProxyAppenderBuilder",
			"handlers" : [
				{
					"bubble" : true,
					"builderClass" : "swf\\lf4php\\config\\builder\\monolog\\MonologStreamHandlerBuilder",
					"stream" : "${LOG_DIR}/application.log",
					"formatter": {
						"builderClass" : "swf\\lf4php\\config\\builder\\monolog\\MonologLineFormatterBuilder",
						"format" : "[%datetime%] %extra.loggerName%.%level_name%: %message% %context% %extra%\n\n"
					}
				},
				{
					"bubble" : true,
					"level" : "ERROR",
					"builderClass" : "swf\\lf4php\\config\\builder\\monolog\\MonologStreamHandlerBuilder",
					"stream" : "${LOG_DIR}/error.log",
					"formatter": {
						"builderClass" : "swf\\lf4php\\config\\builder\\monolog\\MonologLineFormatterBuilder",
						"format" : "[%datetime%] %extra.loggerName%.%level_name%: %message% %context% %extra%\n\n"
					}
					
				}				
			]
		}
	],

	"loggers" : [
		{
			"name" : "swf.lf4php.examples.namespaceA",
			"level" : "DEBUG",
			"appenders" : "MainLogFiles"
		},
		{
			"name" : "swf.lf4php.examples.namespaceB",
			"level" : "ERROR",
			"appenders" : "MainLogFiles"
		},
		{
			"level" : "INFO",
			"appenders" : ["MainLogFiles"]
		}
	]
}
```
