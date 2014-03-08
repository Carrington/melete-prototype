<?php
/**
 * Validators for all Task member variables that are subject to validation.
 * 
 * TaskRules is responsible for ensuring data validity/conformation to the 
 * business rules for tasks that are established via configuration.
 *
 * @author Dan
 */

namespace Melete\Rules;

class TaskRules extends AbstractRules
{
    
    public function __construct() {
    }
    
    public function validateName($name) {
        //validate name length
        $length = ($name <= $this->getConfigValue('name.length'));
        //validate name includes no unpermitted characters
        $characters = (preg_match($this->getConfigValue('name.characters')));
        return ($length && $characters);
    }
    
    public function validateInterval($interval) {
        //validate that interval is <= minimum 
        $min = ($this->getConfig('interval.minimum') <= $interval);
        //validate that interval is >= maximum
        $max = ($interval <= $this->getConfigValue('interval.maximum'));
        return ($min && $max);
    }
    
    public function validateDailyLimit($user) {
        if ($user->getDailyLimitOverride() && 
            $this->getConfigValue('user-limits.override')) {
            return ($user->getSentToday() <= $user->getDailyLimit());
        }
        return ($user->getSentToday() <= $this
                ->getConfigValue('user-limits.daily'));
    }
    
    public function validateWeeklyLimit($user) {
        if ($user->getWeeklyLimitOverride() &&
                $this->getConfigValue('user-limits.override')) {
            return ($user->getSentThisWeek() <= $user->getWeeklyLimit());
        }
        return ($user->getSentThisWeek() <= $this
                ->getConfigValue('user-limits.weekly'));
    }
    
    public function validateMonthlyLimit($user) {
        if ($user->getMonthlyLimitOverride() &&
                $this->getConfigValue('user-limits.override')) {
            return ($user->getSentThisMonth() <= $user->getMonthlyLimit());
        }
        return ($user->getSentThisMonth() <= $this
                ->getConfigValue('user-limits.monthly'));
    }
    
    public function getConfigValue($value) {
        $this->configuration->getConfigValue($value);
    }
    
       
}

?>
