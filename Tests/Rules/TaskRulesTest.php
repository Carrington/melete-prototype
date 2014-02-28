<?php

namespace Melete\Tests\Rules;

use Melete\Rules\TaskRules.php as Rules;
use PHPUnit\Framework\TestCase as TestCase;
 
class TaskRulesTest extends TestCase 
{
	protected $taskRulesObj;

	public function setUp() {
		$this->taskRulesObj = new Rules();
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
	public function testValidateTaskNameLengthNegative() {
		$this->assertFalse($this->taskRulesObj->validateName("AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"));
	}

	/**
	 * @depends testValidateTaskNameLengthNegative
	 */
	public function testValidateTaskNameLengthPositive() {
		$this->assertTrue($this->taskRulesObj
                        ->validateName("AAAAAAAAAA"));
	}

	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskNameCharactersNegative() {
		$this->assertFalse($this->taskRulesObj
                        ->validateName("Th!sisaname"));
	}


	/**
	 * @depends testValidateTaskNameCharactersNegative
	 */
	public function testValidateTaskNameCharactersPositive() {
		$this->assertTrue($this->taskRulesObj
                        ->validateName("Thisisaname"));
	}
	
	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskMinimumIntervalNegative() {
		$this->assertFalse($this->taskRulesObj->validateInterval(299));
	}

	/**
	 * @depends testValidateTaskMinimumIntervalNegative
	 */
	public function testValidateTaskMinimumIntervalPositive() {
		$this->assertTrue($this->taskRulesObj
                        ->validateInterval(300));
        }
	
	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskMaximumIntervalNegative() {
		$this->assertFalse($this->taskRulesObj
                        ->validateInterval(788940001));
	}

	/**
	 * @depends testValidateTaskMaximumIntervalNegative
	 */
	public function testValidateTaskMaximumIntervalPositive() {
		$this->assertTrue(788940000);
	}

	/**
	 * @depends testLoadConfig
         * @dataProvider provideUserMock
	 */
	public function testValidateTaskGlobalLimitDailyNegative($user) {
            $user->expects($this->once())->method('getDailyLimitOverride')
                    ->will($this->returnValue(false));
            $user->expects($this->once())->method('getUserID')
                    ->will($this->returnValue(1));
            //implies TaskRules keeps a list of users to check.
            $this->taskRulesObj->clearUsers();
            $this->taskRulesObj->addUser($user);
            //implies current count of daily reminders, current user ID in 
            //question
            $this->assertFalse($this->taskRulesObj->validateDailyLimit(51, 1));
	}

	/**
	 * @depends testValidateTaskGlobalLimitDailyNegative
         * @dataProvider provideUserMock
	 */
	public function testValidateTaskUserLimitDailyPositive($user) {
            $user->expects($this->once())->method('getDailyLimitOverride')
                    ->will($this->returnValue(true));
            $user->expects($this->once())->method('getDailyLimit')
                    ->will($this->returnValue(60));
            $user->expects($this->once())->method('getUserID')
                    ->will($this->returnValue(1));
            $this->taskRulesObj->clearUsers();
            $this->taskRulesObj->addUser($user);
            $this->assertTrue($this->taskRulesObj->validateDailyLimit(51, 1));
	}
        
        /**
         * @depends testValidateTaskUserLimitDailyPositive
         * @dataProvider provideUserMock
         */
        public function testValidateTaskUserLimitDailyNegative($user) {
            $user->expects($this->once())->method('getDailyLimitOverride')
                    ->will($this->returnValue(true));
            $user->expects($this->once())->method('getDailyLimit')
                    ->will($this->returnValue(40));
            $user->expects($this->once())->method('getUserID')
                    ->will($this->returnValue(1));
            $this->taskRulesObj->clearUsers();
            $this->taskRulesObj->addUser($user);
            $this->assertTrue($this->taskRulesObj->validateDailyLimit(51, 1));
        }
        
        /**
	 * @depends testLoadConfig
         * @dataProvider provideUserMock
	 */
	public function testValidateTaskGlobalLimitWeeklyNegative($user) {
            $user->expects($this->once())->method('getWeeklyLimitOverride')
                    ->will($this->returnValue(false));
            $user->expects($this->once())->method('getUserID')
                    ->will($this->returnValue(1));
            $this->taskRulesObj->clearUsers();
            $this->taskRulesObj->addUser($user);
            $this->assertFalse($this->taskRulesObj->validateWeeklyLimit(51, 1));
	}
	
        /**
	 * @depends testValidateTaskGlobalLimitWeeklyNegative
         * @dataProvider provideUserMock
	 */
	public function testValidateTaskUserLimitWeeklyPositive($user) {
            $user->expects($this->once())->method('getWeeklyLimitOverride')
                    ->will($this->returnValue(true));
            $user->expects($this->once())->method('getWeeklyLimit')
                    ->will($this->returnValue(60));
            $user->expects($this->once())->method('getUserID')
                    ->will($this->returnValue(1));
            $this->taskRulesObj->clearUsers();
            $this->taskRulesObj->addUser($user);
            $this->assertTrue($this->taskRulesObj->validateWeeklyLimit(51, 1));
	}
        
        /**
         * @depends testValidateTaskUserLimitWeeklyPositive
         * @dataProvider provideUserMock
         */
        public function testValidateTaskUserLimitWeeklyNegative($user) {
            $user->expects($this->once())->method('getWeeklyLimitOverride')
                    ->will($this->returnValue(true));
            $user->expects($this->once())->method('getWeeklyLimit')
                    ->will($this->returnValue(40));
            $user->expects($this->once())->method('getUserID')
                    ->will($this->returnValue(1));
            $this->taskRulesObj->clearUsers();
            $this->taskRulesObj->addUser($user);
            $this->assertTrue($this->taskRulesObj->validateWeeklyLimit(51, 1));
        }
        
        /**
	 * @depends testLoadConfig
         * @dataProvider provideUserMock
	 */
	public function testValidateTaskGlobalLimitMonthlyNegative($user) {
            $user->expects($this->once())->method('getMonthlyLimitOverride')
                    ->will($this->returnValue(false));
            $user->expects($this->once())->method('getUserID')
                    ->will($this->returnValue(1));
            $this->taskRulesObj->clearUsers();
            $this->taskRulesObj->addUser($user);
            $this->assertFalse($this->taskRulesObj->validateMonthlyLimit(51, 1));
	}
	
        /**
	 * @depends testValidateTaskGlobalLimitMonthlyNegative
         * @dataProvider provideUserMock
	 */
	public function testValidateTaskUserLimitMonthlyPositive($user) {
            $user->expects($this->once())->method('getMonthlyLimitOverride')
                    ->will($this->returnValue(true));
            $user->expects($this->once())->method('getMonthlyLimit')
                    ->will($this->returnValue(60));
            $user->expects($this->once())->method('getUserID')
                    ->will($this->returnValue(1));
            $this->taskRulesObj->clearUsers();
            $this->taskRulesObj->addUser($user);
            $this->assertTrue($this->taskRulesObj->validateMonthlyLimit(51, 1));
	}
        
        /**
         * @depends testValidateTaskUserLimitMonthlyPositive
         * @dataProvider provideUserMock
         */
        public function testValidateTaskUserLimitMonthlyNegative($user) {
            $user->expects($this->once())->method('getMonthlyLimitOverride')
                    ->will($this->returnValue(true));
            $user->expects($this->once())->method('getMonthlyLimit')
                    ->will($this->returnValue(40));
            $user->expects($this->once())->method('getUserID')
                    ->will($this->returnValue(1));
            $this->taskRulesObj->clearUsers();
            $this->taskRulesObj->addUser($user);
            $this->assertTrue($this->taskRulesObj->validateMonthlyLimit(51, 1));
        }
        
        /**
         * @depends testLoadConfig
         * @dataProvider provideUserMock
         */
        public function testValidateTaskUserLevelNegative($user) {
            $user->expects($this->once())->method('getUserAccountType')
                    ->will($this->returnValue('guest'));
            $user->expects($this->once())->method('getUserID')
                    ->will($this->returnValue(1));
            $this->taskRulesObj->clearUsers();
            $this->taskRulesObj->addUser($user);
            $this->assertFalse($this->taskRulesObj->validateUserCreateTask(1));
        }
        
        /**
         * @depends testValidateTaskUserLevelNegative
         * @dataProvider provideUserMock
         */
        public function testValidateTaskUserLevelPositive($user) {
            $user->expects($this->once())->method('getUserAccountType')
                    ->will($this->returnValue('registered'));
            $user->expects($this->once())->method('getUserID')
                    ->will($this->returnValue(1));
            $this->taskRulesObj->clearUsers();
            $this->taskRulesObj->addUser($user);
            $this->assertTrue($this->taskRulesObj->validateUserCreateTask(1));
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
