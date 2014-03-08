<?php

namespace Melete\Business;

/**
 * Description of ConfigurationProvider
 *
 * @author Dan
 */
class ConfigurationProvider
{
    private $configurationLoader;
            
    public function __construct(Melete\Business\Helpers\LoaderInterface $loader) {
        $this->configurationLoader = $loader;
    }
    
    public function getConfigValue($value) {
        
        $configMap = $this->getConfigAsMap();
        $valueKeys = explode('.', $value);
        $value = $configMap;
        for($keyIterator = 0; $keyIterator < count($valueKeys); $keyIterator++) {
            if (array_key_exists($valueKeys[$keyIterator], $value)) {
                $value = $value[$valueKeys[$keyIterator]];
            } else {
                throw new Melete\Exceptions\ConfigException("No configuration value: " . $valueKeys);
            }
        }
        return $value;
    }
    
    private function getConfigAsMap() {
        //stub        
    }
}

?>
