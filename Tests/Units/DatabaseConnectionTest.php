<?php
namespace Tests\Units;

use App\Contracts\DatabaseConnectionInterface;
use App\Database\MySQliConnection;
use App\Database\PDOConnection;
use App\Exception\MissingArgumentException;
use App\Helpers\Config;
use mysqli;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase {
    private function getCredentials( string $type ) {
        return array_merge( Config::get( 'database', $type ), ['db_name' => 'bug_testing'] );
    }

    public function testItCanConnectToDatabaseWithPdoApi() {
        $credentials = $this->getCredentials( 'pdo' );
        $pdoHandler = ( new PDOConnection( $credentials ) )->connect();
        self::assertInstanceOf( DatabaseConnectionInterface::class, $pdoHandler );

        return $pdoHandler;
    }

    /** @depends testItCanConnectToDatabaseWithPdoApi */

    public function testItIsAValidMySQliConnection( DatabaseConnectionInterface $handler ) {
        self::assertInstanceOf( PDO::class, $handler->getConnection() );
    }

    public function testItCanConnectToDatabaseWithMySQliApi() {
        $credentials = $this->getCredentials( 'mysqli' );
        $handler = ( new MySQliConnection( $credentials ) )->connect();
        self::assertInstanceOf( DatabaseConnectionInterface::class, $handler );

        return $handler;
    }

    /** @depends testItCanConnectToDatabaseWithMySQliApi */

    public function testItIsAValidPDOConnection( DatabaseConnectionInterface $handler ) {
        self::assertInstanceOf( mysqli::class, $handler->getConnection() );
    }

    public function testIsThrowMissingArgumentException() {
        self::expectException( MissingArgumentException::class );

        $credentials = [];
        $pdoHander = new PDOConnection( $credentials );

    }

}
