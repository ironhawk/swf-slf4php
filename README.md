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
			"name" : "your.namespace.A",
			"level" : "DEBUG",
			"appenders" : ["logFile", "console"]
		},
		{
			"name" : "your.namespace.B",
			"level" : "INFO",
			"appenders" : "logFile"
		},
		{
			"level" : "WARNING",
			"appenders" : "logFile"
		}
	]
}
```

With the above simple config we have:
   * Created two Appenders: a log file and a console. Using [Monolog](https://github.com/Seldaek/monolog) `StreamHandler`
   * Created a DEBUG level Logger for "your.namespace.A" which is using both the log file Appender and the console Appender
   * Created an INFO level Logger for "your.namespace.B" which is using the log file Appender only
   * And created a WARNING level Logger using the log file Appender too. AND... You can notice that this one has no namespace definition... Which means that this is the *default* logger setup
   
# Basic usage  
  
Assuming you have the above config saved in file named **log.config.json** you can configure the `LoggerFactory` like this:
  
```php
<?php
use swf\lf4php\LoggerFactory;
use swf\lf4php\config\builder\LogConfigBuilder;
use swf\util\JsonUtil;

....

// you might use variables in the json file (unix like style) and we can pass an array to the json parsing mechanism
// to let it resolve the vars
$envVars = [
	'LOG_DIR' => '/var/log'
];
$loggerConfig = LogConfigBuilder::create()->initFromJson(JsonUtil::getJsonObjects("log.config.json"), $envVars)->build();
// and now we can init the factory
LoggerFactory::init($loggerConfig);
```
  
Once you have configured the `LoggerFactory` you can start get `Logger` instances from the factory like this:

```php
$myLogger = LoggerFactory::getLogger(YourClass::class);
$myLogger->info("This is a log message", []);
```

As you might have already figured this out when you get a Logger from the factory you will get back the Logger which is matching the configuration!

In this case:
```php
$logger = LoggerFactory::getLogger('\your\namespace\A\MyClass');
```
you will get back a DEBUG level Logger instance with the logFile and the console Appenders behind this.

While in this case:
```php
$logger = LoggerFactory::getLogger('JustAClass');
```
it will be the *default* logger which is matching so you will get back a WARNING level Logger instance with the logFile Appender behind this.



# Features

   * implements psr/log interface
   * we have Loggers and Appenders (output channel) - a Logger can be configured to use multiple Appenders at the same time
   * you can create your own Appenders easily by implementing the interface
   * framework has `MonologProxyAppender` out of the box so you can use [Monolog](https://github.com/Seldaek/monolog) as an implementation right now
   * you can configure your Loggers on a namespace (aka package) basis which allows you to use different log levels / output channels (Appenders) at different packages (just like we regularly do in Java!)
   * if you are not configuring up logging then `LoggerFactory` will default to returning 'no operation' Loggers - you can configure logging later anytime
   * builder based architecture - for building up configuration easily
   * all config builder classes support JSON format so you can define your config in JSON files quickly

   
