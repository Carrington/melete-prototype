<?php

namespace Melete\Tests\Rules;

use Melete\Rules\TaskRules as Rules;
 
class TaskRulesTest extends \PHPUnit_Framework_TestCase 
{
	protected $taskRulesObj;

	public function setUp() {
                //At this time I can't come up with any way to inject a mock 
                //dependency that would allow us to test UserList::clear().
		$this->taskRulesObj = new Rules();
	}

	/**
	 */
	public function testLoadConfig() {
                $config = $this->provideConfigMock();
		$this->taskRulesObj->loadConfig($config);
		
		$this->assertSame($this->taskRulesObj->loadConfig(), $config);
	}

	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskNameLengthNegative() {
                $config = $this->provideConfigMock();
		$this->taskRulesObj->loadConfig($config);
		$this->assertFalse($this->taskRulesObj->validateName("AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"));
	}

	/**
	 * @depends testValidateTaskNameLengthNegative
	 */
	public function testValidateTaskNameLengthPositive() {
                $config = $this->provideConfigMock();
		$this->taskRulesObj->loadConfig($config);
		$this->assertTrue($this->taskRulesObj
                        ->validateName("AAAAAAAAAAA"));
	}

	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskNameCharactersNegative() {
                $config = $this->provideConfigMock();
		$this->taskRulesObj->loadConfig($config);
		$this->assertFalse($this->taskRulesObj
                        ->validateName("Th!sisaname"));
	}


	/**
	 * @depends testValidateTaskNameCharactersNegative
	 */
	public function testValidateTaskNameCharactersPositive() {
		$config = $this->provideConfigMock();
		$this->taskRulesObj->loadConfig($config);
                $this->assertTrue($this->taskRulesObj
                        ->validateName("Thisisaname"));
	}
	
	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskMinimumIntervalNegative() {
                $config = $this->provideConfigMock();
		$this->taskRulesObj->loadConfig($config);
                $this->assertFalse($this->taskRulesObj->validateInterval(299));
	}

	/**
	 * @depends testValidateTaskMinimumIntervalNegative
	 */
	public function testValidateTaskMinimumIntervalPositive() {
                $config = $this->provideConfigMock();
		$this->taskRulesObj->loadConfig($config);
                $this->assertTrue($this->taskRulesObj
                        ->validateInterval(300));
        }
	
	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskMaximumIntervalNegative() {
                $config = $this->provideConfigMock();
		$this->taskRulesObj->loadConfig($config);
                $this->assertFalse($this->taskRulesObj
                        ->validateInterval(788940001));
	}

	/**
	 * @depends testValidateTaskMaximumIntervalNegative
	 */
	public function testValidateTaskMaximumIntervalPositive() {
		$config = $this->provideConfigMock();
		$this->taskRulesObj->loadConfig($config);
                $this->assertTrue($this->taskRulesObj
                        ->validateInterval(788940000));
	}

	/**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskGlobalLimitDailyNegative() {
            $user = $this->provideUserMock();
            $user->expects($this->once())->method('getDailyLimitOverride')
                    ->will($this->returnValue(false));
            $user->expects($this->exactly(2))->method('getSentToday')
                    ->will($this->returnValue(51));
            $config = $this->provideConfigMock();
	    $this->taskRulesObj->loadConfig($config);
            $this->assertFalse($this->taskRulesObj->validateDailyLimit($user));
	}

	/**
	 * @depends testValidateTaskGlobalLimitDailyNegative
	 */
	public function testValidateTaskUserLimitDailyPositive() {
            $user = $this->provideUserMock();
            $user->expects($this->once())->method('getDailyLimitOverride')
                    ->will($this->returnValue(true));
            $user->expects($this->once())->method('getDailyLimit')
                    ->will($this->returnValue(60));
            $user->expects($this->exactly(2))->method('getSentToday')
                    ->will($this->returnValue(51));
            $config = $this->provideConfigMock();
            $this->taskRulesObj->loadConfig($config);
            $this->assertTrue($this->taskRulesObj->validateDailyLimit($user));
	}
        
        /**
         * @depends testValidateTaskUserLimitDailyPositive
         */
        public function testValidateTaskUserLimitDailyNegative() {
            $user = $this->provideUserMock();
            $user->expects($this->once())->method('getDailyLimitOverride')
                    ->will($this->returnValue(true));
            $user->expects($this->once())->method('getDailyLimit')
                    ->will($this->returnValue(40));
            $user->expects($this->exactly(2))->method('getSentToday')
                    ->will($this->returnValue(51));
            
                $config = $this->provideConfigMock();
		$this->taskRulesObj->loadConfig($config);            
            
            $this->assertFalse($this->taskRulesObj->validateDailyLimit($user));
        }
        
        /**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskGlobalLimitWeeklyNegative() {
            $user = $this->provideUserMock();
            $user->expects($this->once())->method('getWeeklyLimitOverride')
                    ->will($this->returnValue(false));
            $user->expects($this->exactly(2))->method('getSentThisWeek')
                    ->will($this->returnValue(301));
            
            $config = $this->provideConfigMock();
	    $this->taskRulesObj->loadConfig($config);           
            
            $this->assertFalse($this->taskRulesObj->validateWeeklyLimit($user));
	}
	
        /**
	 * @depends testValidateTaskGlobalLimitWeeklyNegative
	 */
	public function testValidateTaskUserLimitWeeklyPositive() {
            $user = $this->provideUserMock();
            $user->expects($this->once())->method('getWeeklyLimitOverride')
                    ->will($this->returnValue(true));
            $user->expects($this->once())->method('getWeeklyLimit')
                    ->will($this->returnValue(60));
            $user->expects($this->exactly(2))->method('getSentThisWeek')
                    ->will($this->returnValue(51));
            $config = $this->provideConfigMock();
	    $this->taskRulesObj->loadConfig($config);                  
            
            
            $this->assertTrue($this->taskRulesObj->validateWeeklyLimit($user));
	}
        
        /**
         * @depends testValidateTaskUserLimitWeeklyPositive
         */
        public function testValidateTaskUserLimitWeeklyNegative() {
            $user = $this->provideUserMock();
            $user->expects($this->once())->method('getWeeklyLimitOverride')
                    ->will($this->returnValue(true));
            $user->expects($this->once())->method('getWeeklyLimit')
                    ->will($this->returnValue(40));
            $user->expects($this->exactly(2))->method('getSentThisWeek')
                    ->will($this->returnValue(51));
            $config = $this->provideConfigMock();
	    $this->taskRulesObj->loadConfig($config);                  
            
            
            $this->assertFalse($this->taskRulesObj->validateWeeklyLimit($user));
        }
        
        /**
	 * @depends testLoadConfig
	 */
	public function testValidateTaskGlobalLimitMonthlyNegative() {
            $user = $this->provideUserMock();
            $user->expects($this->once())->method('getMonthlyLimitOverride')
                    ->will($this->returnValue(false));
            $user->expects($this->exactly(2))->method('getSentThisMonth')
                    ->will($this->returnValue(9000));
            $config = $this->provideConfigMock();
	    $this->taskRulesObj->loadConfig($config);                  
            
            
            $this->assertFalse($this->taskRulesObj->validateMonthlyLimit($user));
	}
	
        /**
	 * @depends testValidateTaskGlobalLimitMonthlyNegative
	 */
	public function testValidateTaskUserLimitMonthlyPositive() {
            $user = $this->provideUserMock();
            $user->expects($this->once())->method('getMonthlyLimitOverride')
                    ->will($this->returnValue(true));
            $user->expects($this->once())->method('getMonthlyLimit')
                    ->will($this->returnValue(6000));
            $user->expects($this->exactly(2))->method('getSentThisMonth')
                    ->will($this->returnValue(5999));
            $config = $this->provideConfigMock();
	    $this->taskRulesObj->loadConfig($config);                  
            
            
            $this->assertTrue($this->taskRulesObj->validateMonthlyLimit($user));
	}
        
        /**
         * @depends testValidateTaskUserLimitMonthlyPositive
         */
        public function testValidateTaskUserLimitMonthlyNegative() {
            $user = $this->provideUserMock();
            $user->expects($this->once())->method('getMonthlyLimitOverride')
                    ->will($this->returnValue(true));
            $user->expects($this->once())->method('getMonthlyLimit')
                    ->will($this->returnValue(4000));
            $user->expects($this->exactly(2))->method('getSentThisMonth')
                    ->will($this->returnValue(5100));
            $config = $this->provideConfigMock();
	    $this->taskRulesObj->loadConfig($config);                  
            
            
            $this->assertFalse($this->taskRulesObj->validateMonthlyLimit($user));
        }
        
        /**
         * deprecated - move this functionality to UserRules - ACL.
         */
        /**
        public function testValidateTaskUserLevelNegative() {
            $user = $this->provideUserMock();
            $user->expects($this->once())->method('getUserAccountType')
                    ->will($this->returnValue('guest'));
            $user->expects($this->once())->method('getSentToday')
                    ->will($this->returnValue(51));
            
            
            
            $this->assertFalse($this->taskRulesObj->validateUserCreateTask($user));
        }
        
        /**
         * deprecated - move this functionality to UserRules - ACL.
         */
        /**
         public function testValidateTaskUserLevelPositive($user) {
            $user->expects($this->once())->method('getUserAccountType')
                    ->will($this->returnValue('registered'));
            $user->expects($this->once())->method('getSentToday'))
                    ->will($this->returnValue(51);
            
            
            
            $this->assertFalse($this->taskRulesObj->validateUserCreateTask($user));
        }**/

	/**
	 * Mock for Melete\Business\ConfigurationProvider
	 */
	public function provideConfigMock()
	{
                
		$configProvider = $this
                       ->getMockBuilder('Melete\Business\ConfigurationProvider')
                       ->disableOriginalConstructor()
                       ->setMethods(array('getConfigAsMap','loadConfig','getConfigValue'))
                       ->getMock();
		$response =  array(
			//task.name
			"name" => array (
				//task.name.length
				"length" => 100,
				//task.name.characters
				"characters" => "/[^a-zA-Z0-9_ \-]/s"
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
                $configProvider->expects($this->any())
                        ->method('getConfigValue')
                        ->will($this->returnCallback(function($value) {
                            switch($value) {
                                case 'name.length':
                                    return 100;
                                    break;
                                case 'name.characters':
                                    return '/[^a-zA-Z0-9_ \-]/s';
                                    break;
                                case 'interval.minimum':
                                    return 300;
                                    break;
                                case 'interval.maximum':
                                    return 788940000;
                                    break;
                                case 'user-limits.daily':
                                    return 50;
                                    break;
                                case 'user-limits.weekly':
                                    return 300;
                                    break;
                                case 'user-limits.monthly':
                                    return 900;
                                    break;
                                case 'user-limits.override':
                                    return true;
                                    break;
                                case 'minimum-account':
                                    return 'registered';
                                    break;
                            }
                        }));
                
                

		$configProvider->expects($this->any())
                                ->method('getConfigAsMap')
                                ->will($this->returnValue($response));
		return $configProvider;

	}

	/**
	 * Mock for Melete\Business\User
	 */
	public function provideUserMock() {
		$user = $this->getMockBuilder('Melete\Business\User')
	                ->disableOriginalConstructor()
        	        ->getMock();
		return $user;
	}

	


}


?>
