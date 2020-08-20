<?php

namespace App\Exception;

use App\Helpers\App;
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

}