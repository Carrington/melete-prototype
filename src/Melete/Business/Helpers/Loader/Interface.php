<?php

/**
 * Uniform interface for all config loaders.
 * 
 * @author Dan
 */

namespace Melete\Business\Helpers;

interface LoaderInterface {
    
    public function loadConfig();
    public function unloadConfig();
    
}
?>
