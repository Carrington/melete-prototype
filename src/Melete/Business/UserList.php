<?php
namespace Melete\Business;

/**
 * Description of UserList
 *
 * @author Dan
 */
class UserList extends \SplQueue
{
    public function clearUsers() {
        if ($this->users->isEmpty()) {
            return;        
        }
        foreach($this->users as $user) {
            //nothing to see here, just dequeueing.
        }
    }
}

?>
