<?php
use swf\lf4php\LoggerFactory;
use swf\lf4php\config\builder\LogConfigBuilder;
use swf\util\JsonUtil;
use swf\lf4php\examples\namespaceA\ClassA;
use swf\lf4php\examples\namespaceB\ClassASubclass;

// let's fire the composer autoload stuff
require_once '../../vendor/autoload.php';

$envVars = [
	'LOG_DIR' => __DIR__
];
$loggerConfig = LogConfigBuilder::create()->initFromJson(JsonUtil::getJsonObjects("log.config.json"), $envVars)->build();
LoggerFactory::init($loggerConfig);


// let's instatiate ClassA on namespaceA - see what logs are generated... (due to config namespaceA is on DEBUG level)
$classA = new ClassA("Inst1");
$classA->testLog();

// and now let's instatiate ClassAExtended
// this extends ClassA but adds a __toString() function to that
// please also note: protected static variable $_LOG is overriden ...
$classB1 = new ClassASubclass("Inst1");
$classB1->testLog();

