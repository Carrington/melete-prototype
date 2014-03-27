<?php

use Melete\Database\IlluminateDatabaseBootstrap;
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-03-23 at 06:31:45.
 * 
 * Pseudocode Usage Outline:
 * 
 * Create new Illuminate\ConnFactory
 * Create new connection
 * Create new Connectionresolver
 * Set connection as default
 */
class IlluminateDatabaseBootstrapTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var IlluminateDatabaseBootstrap
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new IlluminateDatabaseBootstrap;
    }
    
    protected function provideConnectionDetails() {
        return array(
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'database' => '',
            'username' => '',
            'password' => '',
            'collation' => 'utf8_general_ci',
            'prefix' => ''
        );
    }
    
    public function testConnectionCreated() {
        $name = 'testConn';
        $this->object->bootstrap($this->provideConnectionDetails(), $name);
        $conn = Illuminate\Support\Facades\DB::connection($name);
        $this->assertAttributeEquals($name, $conn->getName());
        $this->assertInstanceOf("Iluuminate\Database\MySqlConnector", $conn);
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

}
