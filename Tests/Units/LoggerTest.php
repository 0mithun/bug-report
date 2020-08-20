<?php
namespace Tests\Units;

use App\Contracts\LoggerInterface;
use App\Exception\InvalidLogLevelArgument;
use App\Helpers\App;
use App\Logger\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase {
    /**@var Logger $logger */
    private $logger;

    protected function setUp(): void {
        $this->logger = new Logger;
        parent::setUp();
    }

    public function testItImplementsTheLoggerInterface() {
        self::assertInstanceOf( LoggerInterface::class, $this->logger );
    }

    public function testItCanCreateDifferentTypesOfLogLevel() {
        $this->logger->info( 'Testing info logs' );
        $app = new App;
        $fileName = sprintf( "%s/%s-%s.log", $app->getLogPath(), $app->isEnvironment(), date( 'j.n.Y' ) );
        self::assertFileExists( $fileName );
        $contentOfLogFile = file_get_contents( $fileName );
        self::assertStringContainsString( 'Testing info logs', $contentOfLogFile );
        unlink( $fileName );
        self::assertFileDoesNotExist( $fileName );
    }

    public function testItThrowsInvalidLogLevelArgumentException() {
        self::expectException( InvalidLogLevelArgument::class );
        $this->logger->log( 'adfdsf', 'something' );
    }
}
