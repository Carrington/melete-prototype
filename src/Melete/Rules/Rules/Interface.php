<?php

/**
 * Interface for specifying the interface of any Business Rules Object
 * 
 * Specifies the uniform interface of all Rules objects. Because we don't
 * necessarily want to hard-lock particular implementations into one given
 * functionality, we will provide a contract so that the rest of the world
 * doesn't need to much care what ancestor a given Rules object inherits from.
 * 
 * @author Dan
 */

namespace Melete\Rules;

use Melete\Business\Helpers\LoaderInterface;

interface RulesInterface
{
    public function loadConfig(LoaderInterface $config);
    public function getConfig();
}

?>
