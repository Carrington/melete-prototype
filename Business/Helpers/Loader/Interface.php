<?php

/**
 * Uniform interface for all config loaders.
 * 
 * @author Dan
 */

namespace Melete\Business\Helpers;

interface LoaderInterface {
    
    public static function getConfig();
    public static function refreshConfig();
    public static function unloadConfig();
    
}
?>
