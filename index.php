<?php
declare ( strict_types = 1 );

use App\Logger\Logger;

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/Src/Exception/exception.php';

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

$logger = new Logger;

$logger->log( 'info', 'test invalid level' );
$logger->info( 'User created successfully', ['id' => 45] );