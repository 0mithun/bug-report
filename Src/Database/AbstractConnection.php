<?php
namespace App\Database;

use App\Exception\MissingArgumentException;

abstract class AbstractConnection {
    protected $connection;
    protected $credentials;
    const REQUIRED_CONNECTION_KEYS = [];

    public function __construct( array $credentials ) {
        $this->credentials = $credentials;
        if ( !$this->credentialsHasRequiredKeys( $this->credentials ) ) {
            throw new MissingArgumentException( 'Missing some arguments' );
        }
    }

    private function credentialsHasRequiredKeys( array $credentials ): bool {
        $matches = array_intersect( static::REQUIRED_CONNECTION_KEYS, array_keys( $credentials ) );

        return count( $matches ) === count( static::REQUIRED_CONNECTION_KEYS );
    }

    abstract protected function parseCredentials( array $credentials ): array;

}
