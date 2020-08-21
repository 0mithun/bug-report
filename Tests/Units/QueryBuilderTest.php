<?php
namespace Tests\Units;

use App\Database\PDOConnection;
use App\Database\PDOQueryBuilder;
use App\Database\QueryBuilder;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase {
    private $queryBuilder;

    protected function setUp(): void {
        $credentials = array_merge( Config::get( 'database', 'pdo' ), ['db_name' => 'bug_testing'] );

        $pdoConnection = ( new PDOConnection( $credentials ) )->connect();

        $this->queryBuilder = new PDOQueryBuilder( $pdoConnection );

        // $mysqliConnection = ( new MySQliConnection( $credentials ) )->connect();
        // $this->queryBuilder = new MySQLiQueryBuilder( $mysqliConnection );

        parent::setUp();
    }

    public function testItCanCreateRecords() {
        $data = [
            'report_type' => 'test report',
            'message'     => 'This is test report',
            'link'        => 'test report link',
            'email'       => 'test report email',
        ];
        $id = $this->queryBuilder->table( 'reports' )->create( $data );
        self::assertNotNull( $id );
    }

    public function testItCanPerformRawQuery() {
        //
        $results = $this->queryBuilder->raw( "Select * from reports" )->get();
        self::assertNotNull( $results );
    }

    public function testItCanPerformSelectQuery() {
        //
        $results = $this->queryBuilder->table( 'reports' )->select( '*' )->where( 'id', 1 )->first();
        self::assertNotNull( $results );
        self::assertSame( 1, (int) $results->id );
    }

    public function testItCanPerformSelectMultipleWhereClauseQuery() {
        //
        $results = $this->queryBuilder->table( 'reports' )->select( '*' )->where( 'id', 1 )->where( 'report_type', '=', 'test report 1' )->first();
        self::assertNotNull( $results );
        self::assertSame( 'test report 1', $results->report_type );
    }
}
