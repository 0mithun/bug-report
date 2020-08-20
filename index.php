<?php
declare ( strict_types = 1 );

use App\Exception\ExceptionHandler;
use App\Helpers\Config;

require_once __DIR__ . '/vendor/autoload.php';
set_exception_handler( [new ExceptionHandler(), 'handle'] );

// $application = new App;

// echo ( $application->getServerTime()->format( 'Y-M-d h:i:s A' ) ) . PHP_EOL;
// echo ( $application->isDebugMode() ) . PHP_EOL;
// echo ( $application->isEnvironment() ) . PHP_EOL;
// echo ( $application->getLogPath() ) . PHP_EOL;
// echo ( $application->isRunningFromConsole() ) . PHP_EOL;

// if ( $application->isRunningFromConsole() ) {
//     echo 'Run from console';
// } else {
//     echo 'Run from browser';
// }

Config::get( 'addsfsdf' );