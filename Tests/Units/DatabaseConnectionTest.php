<?php
namespace Tests\Units;

use App\Contracts\DatabaseConnectionInterface;
use App\Database\PDOConnection;
use App\Exception\MissingArgumentException;
use App\Helpers\Config;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase {
    private function getCredentials( string $type ) {
        return array_merge( Config::get( 'database', $type ), ['db_name' => 'bug_testing'] );
    }

    public function testItCanConnectToDatabaseWithPdoApi() {
        $credentials = $this->getCredentials( 'pdo' );
        $pdoHander = ( new PDOConnection( $credentials ) )->connect();
        self::assertInstanceOf( DatabaseConnectionInterface::class, $pdoHander );

        return $pdoHander;
    }

    /** @depends testItCanConnectToDatabaseWithPdoApi */

    public function testItIsAValidPDOConnection( DatabaseConnectionInterface $handler ) {
        self::assertInstanceOf( PDO::class, $handler->getConnection() );
    }

    public function testIsThrowMissingArgumentException() {
        self::expectException( MissingArgumentException::class );

        $credentials = [];
        $pdoHander = new PDOConnection( $credentials );

    }

}
