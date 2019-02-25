<?php
/**
 * Class Guestbook
 * the class we want to test
 */
class Guestbook {

	/**
	 * PDObject
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * Guestbook constructor.
	 *
	 * @param $pdo the database connection
	 */
	public function __construct( $pdo ) {
		$this->pdo = $pdo;
	}

	/**
	 * @param $name
	 * @param $address
	 * @param $phone
	 */
	public function addGuest( $name, $address, $phone ) {
		$sql = 'INSERT INTO  guestbook(name, address, phone)  VALUES(:name,:address,:phone)';

		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [
			':name'    => $name,
			':address' => $address,
			':phone'   => $phone,
		] );
	}
}