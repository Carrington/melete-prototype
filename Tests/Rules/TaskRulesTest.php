<?php

namespace Melete\Tests\Rules;

use Melete\Rules\TaskRules.php as Rules;
use PHPUnit\Framework\TestCase as TestCase;
 
class TaskRulesTest extends TestCase 
{
	$taskRulesObj = new Rules();

	/**
	 * @dataProvider provideConfigMock
	 */
	public function testValidateTaskNameLengthNegative($config) {
		$this->taskRulesObj->loadConfig($config);
		$this->assertFalse($this->taskRulesObj->validateName("AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"));
	}

	/**
	 * @depends testValidateTaskNameLengthNegative
	 */
	public function testValidateTaskNameLengthPositive() {
		$this->assertTrue($this->taskRulesObj->validateName("AAAAAAAAAA");
	}

	/**
	 * @dataProvider provideConfigMock
	 */
	public function testValidateTaskNameCharactersNegative($config) {
		$this->taskRulesObj->loadConfig($config);
		$this->assertFalse($this->taskRulesObj->validateName("Th!sisaname");
	}


	/**
	 * @depends testValidateTaskNameCharactersNegative
	 */
	public function testValidateTaskNameCharactersPositive() {
		$this->assertTrue($this->taskRulesObj->validateName("Thisisaname");
	}
	
	/**
	 * @dataProvider providerConfigMock
	 */
	public function testValidateTaskMinimumIntervalNegative($config) {
		$this->taskRulesObj->loadConfig($config);
		$this->assertFalse($this->taskRulesObj->validateInterval(299);
	}

	/**
	 * @depends testValidateTaskMinimumIntervalNegative
	 */
	public function testValidateTaskMinimumIntervalPositive() {
		$this->assertTrue($this->taskRulesObj->validateInterval(300);
	)
	
	/**
	 * @dataProvider provideConfigMock
	 */
	public function testValidateTaskMaximumIntervalNegative($config) {
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
	 * Mocks the interface for 
	 * Melete\Objects\ConfigurationProvider::getConfigAsMap()
	 * where 'TaskRules' is the specified configuration section.
	 */
	public function provideConfigMock()
	{
		return array(
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
				"monthly" => 900
			),
			//task.minimum-account
			"minimum-account" => "registered"
		);
	}

	


}


?>
