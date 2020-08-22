<?php
namespace Tests\Units;

use App\Database\QueryBuilder;
use App\Helpers\DBQueryBuilderFactory;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase {
    private $queryBuilder;

    protected function setUp(): void {
        $this->queryBuilder = DBQueryBuilderFactory::make( 'database', 'mysqli', ['db_name' => 'bug_testing'] );
        $this->queryBuilder->beginTransaction();
        parent::setUp();
    }

    public function testItCanCreateRecords() {
        $id = $this->insertIntoTable();
        self::assertNotNull( $id );
    }

    public function insertIntoTable() {
        $data = [
            'report_type' => 'test report',
            'message'     => 'This is test report',
            'link'        => 'test report link',
            'email'       => 'test report email',
        ];

        return $this->queryBuilder->table( 'reports' )->create( $data );
    }

    public function testItCanPerformRawQuery() {
        $id = $this->insertIntoTable();
        //
        $results = $this->queryBuilder->raw( "Select * from reports" )->get();
        self::assertNotNull( $results );
    }

    public function testItCanPerformSelectQuery() {
        $id = $this->insertIntoTable();
        //
        $results = $this->queryBuilder->table( 'reports' )->select( '*' )->where( 'id', $id )->runQuery()->first();
        self::assertNotNull( $results );
        self::assertSame( $id, $results->id );
    }

    public function testItCanPerformSelectMultipleWhereClauseQuery() {
        $id = $this->insertIntoTable();
        //
        $results = $this->queryBuilder->table( 'reports' )->select( '*' )->where( 'id', $id )->where( 'report_type', '=', 'test report' )->runQuery()->first();
        self::assertNotNull( $results );
        self::assertSame( 'test report', $results->report_type );
        self::assertSame( $id, $results->id );
    }

    protected function tearDown(): void {
        $this->queryBuilder->rollback();
        parent::tearDown();
    }

    public function testItCanFindById() {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder->find( $id );
        // var_dump( $result );exit;
        self::assertNotNull( $result );
        self::assertSame( $id, $result->id );
    }

    public function testItCanFinOnedByGivingValues() {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder->findOneBy( 'report_type', 'test report' );
        self::assertNotNull( $result );
        self::assertSame( $id, $result->id );
        self::assertSame( 'test report', $result->report_type );
    }

    public function testItCanUpdateData() {
        $id = $this->insertIntoTable();

        $reportType = 'update record';
        $newData = [
            'report_type' => $reportType,
            'email'       => 'new',
        ];

        $updated = $this->queryBuilder->table( 'reports' )->update( $newData )->where( 'id', $id )->runQuery()->affected();
        self::assertEquals( 1, $updated );

        $newResult = $this->queryBuilder->find( $id );
        self::assertSame( 'new', $newResult->email );
        // self::assertSame( 'update record', $newResult->report_type );
        self::assertSame( $reportType, $newResult->report_type );

    }

    public function testItCanDeleteGivingId() {
        $id = $this->insertIntoTable();

        $count = $this->queryBuilder->table( 'reports' )->delete()->where( 'id', $id )->runQuery()->affected();

        self::assertEquals( 1, $count );

        $result = $this->queryBuilder->find( $id );
        self::assertNull( $result );
    }
}
