<?php

use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class GuestBookTest
 */
class GuestBookTest extends TestCase {

	use TestCaseTrait;


	/**
	 * the database object
	 * only instantiate pdo once for test clean-up/fixture load.
	 * Do not use a static var here, or you'll run into problems
	 * with your queried tables!
	 *
	 * @var null
	 */
	private $pdo = NULL;


	/**
	 * the database connection
	 * only instantiate PHPUnit\DbUnit\Database\Connection once per test
	 * @var null
	 */
	private $conn = NULL;


	/**
	 * @see https://phpunit.readthedocs.io/en/7.0/database.html#configuration-of-a-phpunit-database-testcase
	 *
	 * @return \PHPUnit\DbUnit\Database\DefaultConnection|null
	 */
	final public function getConnection() {

		if ( $this->conn === NULL ) {

			if ( $this->pdo == NULL ) {

				// this is all we need to connect to our non-persistent database, no user or password is required:
				$this->pdo = new PDO( 'sqlite::memory:' );

				// Because we're creating a completely new database in RAM, we have to create the required tables here.
				// Otherwise we'll get an error because PHPUnit will fail to truncate the tables before testing.
				// Note the SQLite syntax which differs from MySQL you might got merely used to.
				$sql = 'CREATE TABLE IF NOT EXISTS guestbook (
  							id INTEGER PRIMARY KEY AUTOINCREMENT, 
  							name VARCHAR (100) DEFAULT NULL,
  							address TEXT, 
  							phone VARCHAR(50))';
				$this->pdo->exec( $sql );
			}
			$this->conn = $this->createDefaultDBConnection( $this->pdo, ':memory:' );
		}

		return $this->conn;
	}

	/**
	 * load the default values to start with and put them in our db
	 *
	 * @see https://phpunit.readthedocs.io/en/7.0/database.html#configuration-of-a-phpunit-database-testcase
	 *
	 * @return \PHPUnit\DbUnit\DataSet\FlatXmlDataSet
	 */
	public function getDataSet() {

		return $this->createFlatXmlDataSet( './tests/guestbook_fixture.xml' );
	}

	/**
	 * Test the table 'guestbook'. Because the flat XML data set of 'guestbook_fixture.xml'
	 * contains two items, the row count will be two
	 */
	public function testRowCount() {

		$this->assertSame( 2, $this->getConnection()->getRowCount( 'guestbook' ), 'Pre-Condition' );
	}

	/**
	 * Now Test adding a new row
	 */
	public function testAddGuest() {

		// get the class to be tested, providing the PDObject as database connection
		$guestbook = new Guestbook( $this->pdo );

		// insert a new guest
		$guestbook->addGuest( 'Daniel', 'St Kilda, Scotland', '4545' );

		// get the resulting table from our database
		$queryTable = $this->getConnection()->createQueryTable(
			'guestbook', 'SELECT id, name, address, phone FROM guestbook'
		);

		// get the table we would expect after inserting a new guest
		$expectedTable = $this->createFlatXmlDataSet( './tests/guestbook_expected.xml' )->getTable( 'guestbook' );

		// ...and compare both tables ...it passes!
		$this->assertTablesEqual( $expectedTable, $queryTable, "New User Added" );
	}

	/**
	 * Just for fun: let this test fail
	 */
	public function testFailingAddGuest() {

		// get the class to be tested, providing the PDObject as database connection
		$guestbook = new Guestbook( $this->pdo );

		// insert a new guest, but omit the value for 'phone' to let the test fail
		$guestbook->addGuest( 'Daniel', 'St Kilda, Scotland', '' );

		// get the resulting table from our database, , changed by the Guestbook Class
		$queryTable = $this->getConnection()->createQueryTable(
			'guestbook', 'SELECT id, name, address, phone FROM guestbook'
		);

		// get the *expected* table from a flat XML dataset
		$expectedTable = $this->createFlatXmlDataSet( './tests/guestbook_expected.xml' )->getTable( 'guestbook' );

		// ...and compare both tables which will fail
		$this->assertTablesEqual( $expectedTable, $queryTable, 'Failure On Purpose' );
	}
}