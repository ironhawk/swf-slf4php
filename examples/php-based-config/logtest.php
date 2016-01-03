<?php
use wwwind\logging\LoggerFactory;
use wwwind\logging\Logger;
use wwwind\logging\examples\namespaceA\ClassA;
use wwwind\logging\examples\namespaceB\ClassB;


// let's fire the composer autoload stuff
require_once '../../vendor/autoload.php';

define('LOG_DIR', str_replace('\\', '/', __DIR__));


// since config is built up in a separate .php file now we just need to include this
$loggerConfig = require ('log.config.php');
// and we are good to init the factory...
LoggerFactory::init($loggerConfig);


// let's instatiate ClassA on namespaceA - see what logs are generated... (due to config namespaceA is on DEBUG level)
$classA = new ClassA();
$classA->testLog();

// and now let's instatiate ClassB on namespaceB - see what logs are generated... (due to config default Logger is on
// ERROR level only)
$classB = new ClassB();
$classB->testLog();
