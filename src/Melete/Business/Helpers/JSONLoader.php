<?php

namespace Melete\Business\Helpers;

use Melete\Business\Helpers\Loader\LoaderInterface as LoaderInterface;

/**
 * Description of YmlLoader
 *
 * @author Dan
 */
class JSONLoader extends AbstractConfigLoader implements LoaderInterface
{

    private $json;
    private $jsonObj;
    
    public function __construct($file, $wrapper=null, $context=null, $filter=null) {
        parent::__construct($file, $wrapper, $context, $filter);
    }
    
    public function loadConfig() {
	if (! is_resource($this->getFileHandle())) {
		return false;
	}
	if ($this->json == "" || is_null($this->json)) {
		if (! rewind($this->getFileHandle())) {
			throw new \Exception("Could not rewind file pointer");
		}
		clearstatcache();
		$this->json = fread($this->getFileHandle(), filesize($this->getConfigFileName()));
		$this->jsonObj = json_decode($this->json);
	
	}

        if (! is_object($this->jsonObj)) {
                throw new \Exception("JSON did not decode to stdClass properly");
        }

	return $this->jsonObjectToArray($this->jsonObj);	
    }

    private function jsonObjectToArray($jsonObj) {
        $objVars = get_object_vars($jsonObj);
	return $objVars;

    }
 
    private function flagRefreshConfig() {
	$this->json = "";
	$this->jsonObj = null;
    }


    public function unloadConfig() {
        $this->json = "";
        fclose($this->getFileHandle());
    }
    
    public function writeConfig($key, $value) {
       $workingConfig = $this->loadConfig();
       $searchFlag = $this->searchForConfigKey($key, $workingConfig);
       if (! $searchFlag) {
           $workingConfig[$key] = $value;
       } else {
           if ($workingConfig[$key] == $value) {
               return true;
           }
           $keys = explode(' . ', $searchFlag);
           $workingConfig = $this->editConfigValueRecursive($workingConfig, $keys, $value);
	}
        ftruncate($this->getFileHandle(), 0);
	rewind($this->getFileHandle());
        fwrite($this->getFileHandle(), json_encode($workingConfig));
	$this->flagRefreshConfig();
        return true;
    }
    
    private function editConfigValueRecursive($config, $keys, $value) {
        if (! is_array($keys) || count($keys) == 1) {
            return $config[$keys] == $value;
        }
        $key = array_shift($keys);
        $config[$key] = $this->editConfigValueRecursive($config[$key], $keys, $value);
        return $config;
    }
    
    private function searchForConfigKey($needleKey, $workingConfig,$needleValue=null) {
        if (! is_array($workingConfig)) {
            throw new \Melete\Exceptions\ConfigException("Configuration cannot be read");
        }
        return $this->recursiveSearchForKey($needleKey, $needleValue, $workingConfig);
    }
    
    private function recursiveSearchForKey($needleKey, $needleValue, $currentArray) {
        if (! is_array($currentArray)) {
            return false;
        }
        
        foreach ($currentArray as $key => $value) {
            if (($key === $needleKey) && (is_null($needleValue)) ||
                ($key === $needleKey) && ($needleValue == $value)) {
                return $key;
            }
            if (is_array($value)) {
                if ((($key === $needleKey) && 
                      is_null($needleValue)) ||
                    (($key === $needleKey) &&
                     ($value === $needleValue))
                   ) {
                        return $key;
                }
                $checkValue = $this->recursiveSearchForKey($needleKey, $needleValue, $value);
                if($checkValue) {
                    return $key . '.' . $checkValue;
                }
            }
        }
        
        return false;
    }
}

?>
