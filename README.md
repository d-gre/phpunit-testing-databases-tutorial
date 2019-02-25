# Tutorial for Testing Non-Persistent Databases in PHPUnit/DBunit

I've spent quite some time to get this to work, because the official documentation of PHPUnit is unfortunately a little 
bit sketchy, especially for beginners in unit testing databases. (But you should definitely 
[read it](https://phpunit.readthedocs.io/en/latest/database.html) anyway!)

My first tries led me to [this page](https://www.wingsquare.com/blog/phpunit-database-testing/) 
which provides a working example, but you do need a MySQL server and a database that will have its
content changed by executing unit tests.
Instead, my purpose was to get just PHPUnit testing without an active  database server and fresh and clean test 
data for each unit test. So I took the code and changed it to work with a non-persistent
SQLite database in `:memory:`   

## Prerequisites
* Average knowledge of PHP
* PHP >= 7.1.0 (with SQLite Extension [installed](https://stackoverflow.com/questions/8803728/pdo-sqlite-could-not-find-driver-php-file-not-processing))
* [Composer](https://getcomposer.org/) which will install among other:
  * PHPUnit
  * DBUnit (abandoned but still working well for this)

## Installation
Clone this repository, go to its root directory and run 

`composer install`

## Usage
Run the PHPUnit tests: 

`composer test`

Well, it's a tutorial that does not much other than working. Some tests will fail (right on purpose).  Just take a look at the code. I've commented everything you should know or be 
aware of in the main GuestBookTest class, at least I hope so. 

**If there are better ways to do this (which I do not doubt), please open an issue/fork and create a pull request and let me know. Comments and critique are highly appreciated.**