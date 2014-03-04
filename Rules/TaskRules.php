<?php
/**
 * Validators for all Task member variables that are subject to validation.
 * 
 * TaskRules is responsible for ensuring data validity/conformation to the 
 * business rules for tasks that are established via configuration.
 *
 * @author Dan
 */
class TaskRules extends AbstractRules
{
    public function __construct() {
        $this->configuration = array();
    }
    
    public function validateName($name) {
        //validate name length
        $length = ($name <= $this->getConfig()['name']['length']);
        //validate name includes no unpermitted characters
        $characters = (preg_match($this->getConfig()['name']['characters']));
        return ($length && $characters);
    }
    
    public function validateInterval($interval) {
        $min = ($this->getConfig()['interval']['minimum'] <= $interval);
        $max = ($interval <= $this->getConfig()['interval']['maximum']);
        return ($min && $max);
    }
}

?>
