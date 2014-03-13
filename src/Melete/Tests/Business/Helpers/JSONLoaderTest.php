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
    private $loader;
    
    public function setUp() {
        parent::setUp();
        $this->loader = new Loader("/var/www/html/melete-prototype/config/config.json");
    }
    
    /**
     * Should load the config file, then assertSame against an identical object
     */
    public function testloadConfig() {
        $testConfig = $this->loader->loadConfig();
        $assertArray = array(
            "testValue" => true
        );
        $assertConfig = json_encode($assertArray);
        $this->assertSame($assertConfig, $testConfig);
    }
    
    /**
     * @depends testGetConfig
     */
    public function testWriteConfigValue() {
        $key = "monkey";
        $value = "butt";
        $assertArray = array(
            "testValue" => true,
            $key => $value
        );
        $this->loader->writeConfig($key, $value);
        $this->assertEquals($assertArray, $this->loader->loadConfig());
    }
    
    /**
     * @depends testGetConfig
     */
    public function testUnloadConfig() {
        $this->loader->unloadConfig();
        $this->assertFalse($this->loader->loadConfig());
    }
    
    
}

?>
