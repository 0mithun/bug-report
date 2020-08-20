<?php
declare ( strict_types = 1 );
namespace App\Exception;

use Exception;
use Throwable;

abstract class BaseException extends Exception {
    public function __construct( string $message = '', array $data = [], int $code = 0, Throwable $previous = null ) {
        $this->data = $data;
        parent::__construct( $message, $code, $previous );
    }

    /**
     * Set data with key
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setData( string $key, string $value ): void {
        $this->data[$key] = $value;
    }

    /**
     * get extracted data from data array
     * @return array
     */

    public function getExtractedData(): array{
        if ( count( $this->data ) === 0 ) {
            return $this->data;
        }

        return json_decode( json_encode( $this->data ), true );
    }
}