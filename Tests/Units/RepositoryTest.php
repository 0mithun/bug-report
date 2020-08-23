<?php
namespace Tests\Units;

use App\Database\QueryBuilder;
use App\Entity\BugReport;
use App\Helpers\DBQueryBuilderFactory;
use App\Repository\BugReportRepository;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase {
    private $queryBuilder;
    private $bugReportRepository;

    /**@var QueryBuilder $queryBuilder  */
    protected function setUp(): void {
        $this->queryBuilder = DBQueryBuilderFactory::make( 'database', 'mysqli', ['db_name' => 'bug_testing'] );
        $this->queryBuilder->beginTransaction();
        $this->bugReportRepository = new BugReportRepository( $this->queryBuilder );
        parent::setUp();
    }

    public function testItCanCreateRecordWithEntity() {
        $newBugReport = $this->createNewBugReport();

        self::assertInstanceOf( BugReport::class, $newBugReport );
        self::assertNotNull( $newBugReport->getId() );
        self::assertSame( 'type 2', $newBugReport->getReportType() );
        self::assertSame( 'This is a dummy message', $newBugReport->getMessage() );
        self::assertSame( 'this is a test email', $newBugReport->getEmail() );
    }

    public function testItcanUpdateAGivingEntity() {
        $newBugReport = $this->createNewBugReport();

        $BugReport = $this->bugReportRepository->find( $newBugReport->getId() );

        $BugReport->setReportType( 'update report type 2' )->setMessage( 'update dummy message' )->setLink( 'update link' )->setEmail( 'update email' );

        $updatedReport = $this->bugReportRepository->update( $BugReport );

        self::assertInstanceOf( BugReport::class, $updatedReport );
        self::assertNotNull( $updatedReport->getId() );
        self::assertSame( 'update report type 2', $updatedReport->getReportType() );
        self::assertSame( 'update dummy message', $updatedReport->getMessage() );
        self::assertSame( 'update link', $updatedReport->getLink() );
        self::assertSame( 'update email', $updatedReport->getEmail() );
    }

    public function testItCanDeleteAGivingEntity() {
        $newBugReport = $this->createNewBugReport();
        $this->bugReportRepository->delete( $newBugReport );

        $bugReport = $this->bugReportRepository->find( $newBugReport->getId() );

        self::assertNull( $bugReport );

    }

    public function testItCanFindByCriteria() {
        $newBugReport = $this->createNewBugReport();
        $report = $this->bugReportRepository->findBy( [
            ['link', 'test link'],
            ['email', 'this is a test email'],
        ] );

        self::assertIsArray( $report );
        self::assertNotEmpty( $report );
    }

    public function createNewBugReport() {
        $bugReport = new BugReport;
        $bugReport->setReportType( 'type 2' )
            ->setLink( 'test link' )
            ->setMessage( 'This is a dummy message' )
            ->setEmail( 'this is a test email' );

        return $this->bugReportRepository->create( $bugReport );
    }

    protected function tearDown(): void {
        $this->queryBuilder->rollback();
        parent::tearDown();
    }
}