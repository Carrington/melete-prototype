<?php

namespace Melete\Business\Helpers;

/**
 * Description of YmlLoader
 *
 * @author Dan
 */
class JSONLoader implements Melete\Business\Helpers\LoaderInterface
{
    private $configFileName;
    private $fileHandle;
    private $json;
    
    //TODO: Consider abstract ancestor
    public function __construct($file, $wrapper=null, $context=null, $filter=null) {
        parent::__construct($file, $wrapper, $context, $filter);
    }
    
    public function loadConfig() {
        $this->json = fpassthru($this->fileHandle);
        return json_decode($this->json);
    }


    public function unloadConfig() {
        $this->json = "";
        fclose($this->configFileName);
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
           ftruncate($this->fileHandle, 0);
           fwrite($this->fileHandle, json_encode($workingConfig));
           return true;
       }  
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
