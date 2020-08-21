<?php
namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Database\AbstractConnection;
use App\Exception\DatabaseConnectionException;
use mysqli;
use mysqli_driver;
use Throwable;

class MySQliConnection extends AbstractConnection implements DatabaseConnectionInterface {
    const REQUIRED_CONNECTION_KEYS = [
        'driver',
        'host',
        'db_name',
        'username',
        'db_user_password',
        'default_fetch',
    ];

    protected function parseCredentials( array $credentials ): array
    {
        return [$credentials['host'], $credentials['username'], $credentials['db_user_password'], $credentials['db_name']];
    }

    public function connect(): MySQliConnection {
        $credentials = $this->parseCredentials( $this->credentials );

        $driver = new mysqli_driver;
        $driver->report_mode = MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR;

        try {
            $this->connection = new mysqli( ...$credentials );
        } catch ( Throwable $th ) {
            throw new DatabaseConnectionException( $th->getMessage(), $credentials, 500 );
        }

        return $this;

    }

    public function getConnection(): mysqli {
        return $this->connection;
    }
}
