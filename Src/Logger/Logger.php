<?php
namespace App\Logger;

use App\Contracts\LoggerInterface;
use App\Exception\InvalidLogLevelArgument;
use App\Helpers\App;
use ReflectionClass;

class Logger implements LoggerInterface {
    public function debug( string $message, array $context = [] ) {
        $this->addRecord( LogLevel::DEBUG, $message, $context );
    }

    public function info( string $message, array $context = [] ) {
        $this->addRecord( LogLevel::INFO, $message, $context );
    }

    public function notice( string $message, array $context = [] ) {
        $this->addRecord( LogLevel::NOTICE, $message, $context );
    }

    public function warning( string $message, array $context = [] ) {
        $this->addRecord( LogLevel::WARNING, $message, $context );
    }

    public function error( string $message, array $context = [] ) {
        $this->addRecord( LogLevel::ERROR, $message, $context );
    }

    public function critical( string $message, array $context = [] ) {
        $this->addRecord( LogLevel::DEBUG, $message, $context );
    }

    public function alert( string $message, array $context = [] ) {
        $this->addRecord( LogLevel::ALERT, $message, $context );
    }

    public function emergency( string $message, array $context = [] ) {
        $this->addRecord( LogLevel::EMERGENCY, $message, $context );
    }

    public function log( string $level, string $message, array $context = [] ) {
        $object = new ReflectionClass( LogLevel::class );
        $levels = $object->getConstants();
        if ( !in_array( $level, $levels ) ) {
            throw new InvalidLogLevelArgument( $level, $levels );
        }
        $this->addRecord( $level, $message, $context );
    }

    private function addRecord( string $level, string $message, array $context = [] ) {
        $application = new App;
        $date = $application->getServerTime()->format( 'Y-m-d H:i:s' );
        $logPath = $application->getLogPath();
        $env = $application->isEnvironment();

        $details = sprintf( "%s - Level: %s Message %s - Context: %s", $date, $level, $message, json_encode( $context ) ) . PHP_EOL;
        $fileName = sprintf( "%s/%s-%s.log", $logPath, $env, date( 'j.n.Y' ) );
        file_put_contents( $fileName, $details, FILE_APPEND );
    }
}