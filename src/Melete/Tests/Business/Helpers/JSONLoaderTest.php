<?php

namespace Melete\Tests\Business\Helpers;

use Melete\Business\Helpers\JSONLoader as Loader;

/**
 * Description of JSONLoaderTest
 *
 * @author Dan
 */
class JSONLoaderTest extends \PHPUnit_Framework_TestCase
{
    
    public function setUp() {
        parent::setUp();
	$handle = fopen("/var/www/html/melete-prototype/config/config.json", "w");
	ftruncate($handle, 0);
	$string = json_encode(array("testValue" => true));
	fwrite($handle, $string);
	fclose($handle);
    }
    
    /**
     * Should load the config file, then assertSame against an identical object
     */
    public function testLoadConfig() {

	$loader = new Loader("/var/www/html/melete-prototype/config/config.json");

        $testConfig = $loader->loadConfig();
        $assertArray = array(
            "testValue" => true
        );
        $this->assertEquals($assertArray, $testConfig);
    }
    
    /**
     * @depends testLoadConfig
     */
    public function testWriteConfigValue() {

        $loader = new Loader("/var/www/html/melete-prototype/config/config.json");

	$loader->loadConfig();
        $key = "monkey";
        $value = "butt";
        $assertArray = array(
            "testValue" => true,
            $key => $value
        );
        $loader->writeConfig($key, $value);
	$testConfig = $loader->loadConfig();
        $this->assertEquals($assertArray, $testConfig);
    }
    
    /**
     * @depends testLoadConfig
     */
    public function testUnloadConfig() {
	$loader = new Loader("/var/www/html/melete-prototype/config/config.json");
	$loader->loadConfig();
        $loader->unloadConfig();
        $this->assertFalse($loader->loadConfig());
    }
    
    
}

?>
