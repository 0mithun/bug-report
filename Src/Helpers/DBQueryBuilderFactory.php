<?php
namespace App\Helpers;

use App\Database\MySQliConnection;
use App\Database\MySQLiQueryBuilder;
use App\Database\PDOConnection;
use App\Database\PDOQueryBuilder;
use App\Database\QueryBuilder;
use App\Exception\DatabaseConnectionException;
use App\Helpers\Config;

class DBQueryBuilderFactory {
    public static function make( string $credentialFile = 'database', string $connectionType = 'pdo', array $options = [] ): QueryBuilder {
        //
        $connection = null;
        // $credentials = array_merge( Config::get( $credentialFile, $connectionType ), ['db_name' => 'bug_testing'] );
        $credentials = array_merge( Config::get( $credentialFile, $connectionType ), $options );

        switch ( $connectionType ) {
        case 'pdo':
            $connection = ( new PDOConnection( $credentials ) )->connect();

            return new PDOQueryBuilder( $connection );
            break;
        case 'mysqli':
            $connection = ( new MySQliConnection( $credentials ) )->connect();

            return new MySQLiQueryBuilder( $connection );
            break;
        default:
            throw new DatabaseConnectionException( 'Connection type not supported' );
            break;

        }

    }
}