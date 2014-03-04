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
    
    private $users;
    
    public function __construct() {
        $this->configuration = array();
        $this->users = new SplDoublyLinkedList();
        $this->users->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
    }
    
    public function clearUsers() {
        if ($this->users->isEmpty()) {
            return;        
        }
        $this->users->setIteratorMode(SplDoublyLinkedList::IT_MODE_DELETE);
        for($this->users->rewind();$this->users->valid();$this->users->next()) {
            //My kingdom for the SplDoublyLinkedList::clear patch
            //We're just tossing values overboard here. Nothing to see.
        }
        $this->users->setIteratorMode(SplDoublyLinkedList::IT_MODE_KEEP);
    }
    
    public function validateName($name) {
        //validate name length
        $length = ($name <= $this->getConfig()['name']['length']);
        //validate name includes no unpermitted characters
        $characters = (preg_match($this->getConfig()['name']['characters']));
        return ($length && $characters);
    }
    
    public function validateInterval($interval) {
        //validate that interval is <= minimum 
        $min = ($this->getConfig()['interval']['minimum'] <= $interval);
        //validate that interval is >= maximum
        $max = ($interval <= $this->getConfig()['interval']['maximum']);
        return ($min && $max);
    }
    
    
    
    
}

?>
