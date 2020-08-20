<?php

namespace App\Exception;
use App\Helpers\App;
use ErrorException;
use Throwable;

class ExceptionHandler {
    public function handle( Throwable $exception ): void {
        $application = new App;
        if ( $application->isDebugMode() ) {
            var_dump( $exception );
        } else {
            echo 'Something went wrong';
        }
    }

    public function convertWarningAndNoticesToException( $severity, $message, $file, $line ) {
        throw new ErrorException( $message, $severity, $severity, $file, $line );
    }

}