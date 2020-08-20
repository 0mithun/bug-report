<?php
namespace Tests\Units;

use App\Helpers\App;
use DateTime;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase {
    public function testItCanGetInstanceOfApplication() {
        self::assertInstanceOf( App::class, new App );
    }

    public function testItCanGetBasicApplicationDatasetFromAppClass() {
        $application = new App;

        self::assertTrue( $application->isRunningFromConsole() );
        self::assertSame( 'test', $application->isEnvironment() );
        self::assertNotNull( $application->getLogPath() );

        $this->assertInstanceOf( DateTime::class, $application->getServerTime() );
    }
}
