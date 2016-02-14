<?php
use swf\lf4php\LoggerFactory;
use swf\lf4php\Logger;
use swf\lf4php\examples\namespaceA\ClassA;
use swf\lf4php\examples\namespaceB\ClassB;


// let's fire the composer autoload stuff
require_once '../../vendor/autoload.php';

define('LOG_DIR', str_replace('\\', '/', __DIR__));


// since config is built up in a separate .php file now we just need to include this
$loggerConfig = require ('log.config.php');
// and we are good to init the factory...
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
