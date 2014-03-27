<?php

namespace Melete\Tests\ORM;

use Melete\Tests\TestCase as TestCase;
use Melete\ORM\Task as Task;

/**
 * Task Record includes the following fields:
 * 
 * TaskName - varchar(length by config, default=100) (PK)
 * Creator - varchar(length by config, default=50) (PK, FK)
 * DateTimeCreated - datetime (PK)
 * Interval - integer
 * IntervalStart - datetime
 * IntervalEnd - datetime
 * Active - boolean
 * 
 */
class TaskTest extends TestCase
{


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp() {
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    public function testTaskNameIsRequired() {
        $task = new Task();
        $task->creator = $this->getMockUser();
        $task->DateTimeCreated = new \DateTime("2014-03-23 00:00:00");
        
        $this->assertFalse($task->save());
        
        $errors = $task->errors()->all();
        $this->assertCount(1, $errors);
        
        $this->assertEquals("The username field is required.", $errors[0]);
    }
    
    protected function getMockUser() {
        $user = $this->getMockBuilder('\Melete\ORM\User')->setMethods('getUsername')
                ->getMock();
        $user->expects($this->any())->will("TestUser");
        return $user;
    }

}
