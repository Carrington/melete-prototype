<?php
/**
 * Description of AbstractConfigLoader
 *
 * @author Dan
 */

namespace Melete\Business\Helpers;

class AbstractConfigLoader
{

    private $configFileName;
    private $fileHandle;


    public function __construct($file, $wrapper=null, $context=null, $filter=null) {
        $this->configFileName = $file;
        $wrapperString = '';
        
        if (! is_null($wrapper)) {
            if (! stream_wrapper_register($wrapper)) {
                throw new \Melete\Exceptions\ConfigurationStreamException("Protocol handler exists");
            }
            $wrapperString = $wrapper->getWrapperPrefix();
        } else {
            $wrapperString = "file://";
        }
                       
        if (! is_null($context)) {
            stream_context_set_default($context);            
        }
        
        if (! is_file($wrapperString . $file)) {
            throw new \Melete\Exceptions\ConfigException("Specified config file does not exist!");
        } 
        $this->fileHandle = fopen($wrapperString . $file, "r+");
        
        if (! is_null($filter)) {
            stream_filter_append($this->fileHandle, $filter);
        }
    }

    protected function getFileHandle() {
	return $this->fileHandle;
    }

    protected function getConfigFileName() {
	return $this->configFileName;
    }
}

?>
