<?php
/**
 * Description of AbstractConfigLoader
 *
 * @author Dan
 */
class AbstractConfigLoader
{
    public function __construct($file, $wrapper=null, $context=null, $filter=null) {
        $this->configFileName = $file;
        $wrapperString = '';
        
        if (! is_null($wrapper)) {
            if (! stream_wrapper_register($wrapper)) {
                throw new \Melete\Exceptions\ConfigurationStreamException("Protocol handler exists");
            }
            $wrapperString = $wrapper->getWrapperPrefix();
        } else {
            $wrapperString = "php://";
        }
                       
        if (! is_null($context)) {
            stream_context_set_default($context);            
        }
        
        if (! is_file($wrapperString . $file)) {
            throw new \Melete\Exceptions\ConfigException("Specified config file does not exist!");
        } 
        $this->fileHandle = fopen($wrapperString . $file);
        
        if (! is_null($filter)) {
            stream_filter_append($this->fileHandle, $filter);
        }
    }
}

?>
