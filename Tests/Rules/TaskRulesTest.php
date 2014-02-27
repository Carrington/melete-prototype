<?php

namespace Melete\Tests\Rules;

use Melete\Rules\TaskRules.php as Rules;
use PHPUnit\Framework\TestCase as TestCase;
 
class TaskRulesTest extends TestCase 
{
	$taskRulesObj = new Rules();

	public function setUp() {
		
	}

	/**
	 * @dataProvider provideConfigMock
	 */
	public function testLoadConfig($config) {
		$this->taskRulesObj->loadConfig($config);
		$this->assertEquals($this->taskRulesObj->getConfig(), $config->getConfigAsMap());
	}

	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskNameLengthNegative($config) {
		$this->assertFalse($this->taskRulesObj->validateName("AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"));
	}

	/**
	 * @depends testValidateTaskNameLengthNegative
	 */
	public function testValidateTaskNameLengthPositive() {
		$this->assertTrue($this->taskRulesObj->validateName("AAAAAAAAAA");
	}

	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskNameCharactersNegative() {
		$this->assertFalse($this->taskRulesObj->validateName("Th!sisaname");
	}


	/**
	 * @depends testValidateTaskNameCharactersNegative
	 */
	public function testValidateTaskNameCharactersPositive() {
		$this->assertTrue($this->taskRulesObj->validateName("Thisisaname");
	}
	
	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskMinimumIntervalNegative() {
		$this->assertFalse($this->taskRulesObj->validateInterval(299);
	}

	/**
	 * @depends testValidateTaskMinimumIntervalNegative
	 */
	public function testValidateTaskMinimumIntervalPositive() {
		$this->assertTrue($this->taskRulesObj->validateInterval(300);
	)
	
	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskMaximumIntervalNegative() {
		$this->taskRulesObj->loadConfig($config);
		$this->assertFalse($this->taskRulesObj->validateInterval(788940001);
	}

	/**
	 * @depends testValidateTaskMaximumIntervalNegative
	 */
	public function testValidateTaskMaximumIntervalPositive() {
		$this->assertTrue(788940000);
	}

	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskGlobalLimitDailyNegative() {
		$this->assertFalse($this->taskRulesObj->validateDailyLimit(51));
	}

	/**
	 * @depends testValidateTaskGlobalLimitDailyNegative
	 */
	public function testValidateTaskGlobalLimitDailyPositive() {
		$this->assertTrue($this
	}
	


	/**
	 * Mock for Melete\Business\ConfigurationProvider
	 */
	public function provideConfigMock()
	{
		$configProvider = $this->getMockBuilder('Melete\Business\ConfigurationProvider')
					->getMock();
		$response =  array(
			//task.name
			"name" => array (
				//task.name.length
				"length" => 100,
				//task.name.characters
				"characters" => "[a-zA-Z0-9_- ]+"
			),
			//task.interval
			"interval" => array (
				//task.interval.minimum
				"minimum" => 300, //in seconds
				//task.interval.maximum
				"maximum" => 788940000 //in seconds
			),
			//task.user-limits
			"user-limits" => array (
				//task.user-limits.daily
				"daily" => 50,
                                //task.user-limits.weekly
				"weekly" => 300,
				//task.user-limits.monthly
				"monthly" => 900,
				//task.userlimits.override
				"override" => true
			),
			//task.minimum-account
			"minimum-account" => "registered"
		);

		$configProvider->expects($this->once())
                                ->method('getConfigAsMap')
                                ->will($this->returnValue($response));
		return $configProvider;

	}

	/**
	 * Mock for Melete\Business\User
	 */
	public function provideUserMock() {
		$user = $this->getMockBuilder('Melete\Business\User')
	                ->setConstructorArgs()
        	        ->getMock();
		return $user;
	}

	


}


?>
