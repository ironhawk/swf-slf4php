# What is this project?

**lf4php** is similar to the [slf4j](http://www.slf4j.org/) (The Simple Logging Facade for Java) project.

**lf4php** is not a logging implementation! This is more like an abstraction layer on the top of existing logging frameworks like [Monolog](https://github.com/Seldaek/monolog) - just like [slf4j](http://www.slf4j.org/) 

# The concept - in a nut shell

We have the following key entities:

   * **Appenders**  
     You might consider an `Appender` as an output channel. Writes something out to somewhere somehow... (file, database, e-mail, etc.)  
This is the point where an existing logging framework comes to the picture! This project doesn't have own `Appender` implementations. Rather doing that it just gives you "connectors" (typically following the proxy / adapter design patterns) to let you use your favorite logging framework(s).  

   * **Loggers**  
In your code you need a `Logger` instance to log messages. You can invoke the appropriate log method (info(), debug(), warning(), etc.) on it - as defined by the `LoggerInterface` in [PSR/log](https://github.com/php-fig/log)  
Loggers have **log level**. AND... They **have one or more Appenders** behind them! When you log something with your Logger instance this is routed to all the Appenders behind the Logger. So as a result your log message is written to a file, database or sent via e-mail. OR all together!  

   * **LoggerFactory**  
We have a `LoggerFactory`. You ask for the `Logger` instance from the factory - by providing the fully qualified class name of your class you want to do logging from. You get back a `Logger` instance - matching for the logging configuration you have provided to the `LoggerFactory` beforehand.

# And: the configuration

We want to be able to easily configure all the above! To achieve this the best way is having a simple (as possible) **configuration file**.

Take a quick look on the following JSON config and you will immediatelly understand the concept:


```json
{

	"appenders" : [
	
		{
			"name" : "logFile",
			"builderClass" : "swf\\lf4php\\config\\builder\\monolog\\MonologProxyAppenderBuilder",
			"handlers" : [
				{
					"builderClass" : "swf\\lf4php\\config\\builder\\monolog\\MonologStreamHandlerBuilder",
					"stream" : "${LOG_DIR}/application.log",
					"formatter": {
						"builderClass" : "swf\\lf4php\\config\\builder\\monolog\\MonologLineFormatterBuilder",
						"format" : "[%datetime%] %extra.loggerName%.%level_name%: %message% %context% %extra%\n\n"
					}
				}
			]
		},
		{
			"name" : "console",
			"builderClass" : "swf\\lf4php\\config\\builder\\monolog\\MonologProxyAppenderBuilder",
			"handlers" : [
				{
					"builderClass" : "swf\\lf4php\\config\\builder\\monolog\\MonologStreamHandlerBuilder",
					"stream" : "php://stdout",
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
			"name" : "your.namespaceA",
			"level" : "DEBUG",
			"appenders" : "logFile"
		},
		{
			"name" : "your.namespaceB",
			"level" : "ERROR",
			"appenders" : "logFile"
		},
		{
			"level" : "INFO",
			"appenders" : ["logFile", "console"]
		}
	]
}
```

# Features

   * implements psr/log interface
   * we have Loggers and Appenders (output channel) - a Logger can be configured to use multiple Appenders at the same time
   * you can create your own Appenders easily by implementing the interface
   * framework has `MonologProxyAppender` out of the box so you can use [Monolog](https://github.com/Seldaek/monolog) as an implementation right now
   * you can configure your Loggers on a namespace (aka package) basis which allows you to use different log levels / output channels (Appenders) at different packages (just like we regularly do in Java!)
   * if you are not configuring up logging then `LoggerFactory` will default to returning 'no operation' Loggers - you can configure logging later anytime
   * builder based architecture - for building up configuration easily
   * all config builder classes support JSON format so you can define your config in JSON files quickly

   
