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
        $length = (strlen($name) <= $this->getConfigValue('name.length'));
        //validate name includes no unpermitted characters
        $characters = (preg_match($this->getConfigValue('name.characters'), $name));
        return ($length && !$characters);
    }
    
    public function validateInterval($interval) {
        //validate that interval is <= minimum 
        $min = ($this->getConfigValue('interval.minimum') <= $interval);
        //validate that interval is >= maximum
        $max = ($interval <= $this->getConfigValue('interval.maximum'));
        return ($min && $max);
    }
    
    public function validateDailyLimit(\Melete\Business\User $user) {
	if (is_null($user->getSentToday())) {
		throw new \Melete\Exceptions\NullDataException("User's sent reminders were not set.");
	}
	//does the user have the right to override the global daily limit and
	//if so do they have limit remaining?
        if ($user->getDailyLimitOverride() && 
            $this->getConfigValue('user-limits.override')) {
            return ($user->getSentToday() <= $user->getDailyLimit());
        }
	//does the user have limit remaining (against the global limit)?
        return ($user->getSentToday() <= $this
                ->getConfigValue('user-limits.daily'));
    }
    
    public function validateWeeklyLimit(\Melete\Business\User $user) {
        if (is_null($user->getSentThisWeek())) {
                throw new \Melete\Exceptions\NullDataException("User's sent reminders were not set.");
        }

        if ($user->getWeeklyLimitOverride() &&
                $this->getConfigValue('user-limits.override')) {
            return ($user->getSentThisWeek() <= $user->getWeeklyLimit());
        }
        return ($user->getSentThisWeek() <= $this
                ->getConfigValue('user-limits.weekly'));
    }
    
    public function validateMonthlyLimit(\Melete\Business\User $user) {
        if (is_null($user->getSentThisMonth())) {
                throw new \Melete\Exceptions\NullDataException("User's sent reminders were not set.");
        }

        if ($user->getMonthlyLimitOverride() &&
                $this->getConfigValue('user-limits.override')) {
            return ($user->getSentThisMonth() <= $user->getMonthlyLimit());
        }
        return ($user->getSentThisMonth() <= $this
                ->getConfigValue('user-limits.monthly'));
    }
    
    public function getConfigValue($value) {
        $config = $this->loadConfig();
        return $config->getConfigValue($value);
    }
    
    public function validateUserCreateTask(\Melete\Business\User $user) {
        
    }
    
       
}

?>
