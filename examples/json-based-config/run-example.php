<?php
use wwwind\logging\LoggerFactory;
use wwwind\logging\config\builder\LogConfigBuilder;
use wwwind\util\JsonUtil;
use wwwind\logging\examples\namespaceA\ClassA;
use wwwind\logging\examples\namespaceB\ClassB;

// let's fire the composer autoload stuff
require_once '../../vendor/autoload.php';

// let's build up the logger config from a .json file - using the config builder initFromJson() possibility

// you might use variables in the json file (unix like style) and we can pass an array to the json parsing mechanism
// to let it resolve the vars
$envVars = [
	'LOG_DIR' => __DIR__
];
$loggerConfig = LogConfigBuilder::create()->initFromJson(JsonUtil::getJsonObjects("log.config.json"), $envVars)->build();
// and now we can init the factory
LoggerFactory::init($loggerConfig);

// let's instatiate ClassA on namespaceA - see what logs are generated... (due to config namespaceA is on DEBUG level)
$classA = new ClassA("Inst1");
$classA->testLog();

// and now let's instatiate ClassB on namespaceB - see what logs are generated... (due to config namespaceB is on ERROR
// level only)
$classB1 = new ClassB("Inst1");
$classB1->testLog();

// normally you will not do something like this - grab a logger outside a Class ... right?? :-)
// but sometimes we need to do this so - invoking getLogger() without param will return the configured
// default Logger (the one without 'name' attribute in the .json file)
$logger = LoggerFactory::getLogger();

// now let's quickly dump our classB instance and see how an object is dumped - if it has a __toString() method...!
$logger->info("ClassB instance dump (with __toString() present): {}", [], $classB1);

