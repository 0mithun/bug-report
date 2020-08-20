<?php

declare ( strict_types = 1 );
namespace App\Helpers;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Exception;

class App {
    private $config = [];

    public function __construct() {
        $this->config = Config::get( 'app' );
    }

    public function isDebugMode(): bool {
        if ( !isset( $this->config['debug'] ) ) {
            return false;
        }

        return $this->config['debug'];
    }

    public function isEnvironment(): string {
        if ( !isset( $this->config['env'] ) ) {
            return 'production';
        }

        return $this->isTestMode() ? 'test' : $this->config['env'];
    }

    public function getLogPath(): string {
        if ( !isset( $this->config['log_path'] ) ) {
            throw new Exception( 'Log path is not defined' );
        }

        return $this->config['log_path'];
    }

    /**
     * Get is app running in console
     * @return bool
     */

    public function isRunningFromConsole(): bool {
        return php_sapi_name() == 'cli' || php_sapi_name() == 'phpbg';
    }

    /**
     * get current server  date time
     * @return @datetime
     */

    public function getServerTime(): DateTimeInterface {
        return new DateTime( 'now', new DateTimeZone( 'Asia/Dhaka' ) );
    }

    public function isTestMode() {
        if ( !$this->isRunningFromConsole() ) {
            return false;
        }
        if ( defined( 'PHPUNIT_RUNNING' ) && PHPUNIT_RUNNING === true ) {
            return true;
        }

        return false;
    }
}