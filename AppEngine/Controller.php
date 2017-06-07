<?php

//require_once('SentrySDK/lib/Raven/Autoloader.php');
require_once('Modules/SubmitType_Class.php');
require_once('Modules/Modules_Class.php');
require_once('Modules/Auth_Class.php');
require_once('Modules/Rules_Class.php');
require_once('Modules/ProxyType_Class.php');

//Raven_Autoloader::register(); 
//$client = new Raven_Client('https://key@sentry.io/number'); 
//$error_handler = new Raven_ErrorHandler($client); 
//$error_handler->registerExceptionHandler(); 
//$error_handler->registerErrorHandler(); 
//$error_handler->registerShutdownFunction(); 

$SubmitType = new SubmitType;
$Modules    = new Modules;
$Auth       = new Auth;
$Rules      = new Rules;
$ProxyType  = new ProxyType;

$EngineInfo = [
        'Hosts'   =>'https://raw.githubusercontent.com/BurpSuite/CloudGate-List/master/Configuration.json',
        'Module'  =>'https://raw.githubusercontent.com/BurpSuite/RuleList/master/Module/Module',
        'Example' =>'https://raw.githubusercontent.com/BurpSuite/RuleList/master/Example/Example.json',
        'List'    =>'https://raw.githubusercontent.com/BurpSuite/RuleList/master/Default.json',
        'Host'    =>'https',
        'Version' =>'6.0'
    ];

?>