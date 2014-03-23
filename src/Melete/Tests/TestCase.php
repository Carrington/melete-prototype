<?php

namespace Melete\Tests;

/**
 * Based on the work of Zizaco Zizuini
 * @link http://code.tutsplus.com/tutorials/testing-like-a-boss-in-laravel-models--net-30087 
 *
 * @author Dan
 */
class TestCase extends Illuminate\Foundation\Testing\TestCase
{
   /**
   * Default preparation for each test
   */
  public function setUp()
  {
    parent::setUp();
  
    $this->prepareForTests();
  }
  
  /**
   * Creates the application.
   *
   * @return Symfony\Component\HttpKernel\HttpKernelInterface
   */
  public function createApplication()
  {
    $unitTesting = true;
  
    $testEnvironment = 'testing';
  
    return require __DIR__.'/../../start.php';
  }
  
  /**
   * Migrate the database
   */
  private function prepareForTests()
  {
    Artisan::call('migrate');
  }
}

?>
