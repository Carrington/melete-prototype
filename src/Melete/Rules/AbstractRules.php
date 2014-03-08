<?php

/**
 * This file defines the AbstractRules class.
 *
 * AbstractRules defines the basic underlying functionality common to all of
 * the business rules classes within Melete. Business rules classes are used for
 * backend validation and may be queried from the frontend via
 * Melete\Interface\API\RulesValidatorAPI
 * 
 * @author Dan
 * @see Melete\Interface\API\RulesValidatorAPI
 */

namespace Melete\Rules;

use Melete\Rules\RulesInterface;

abstract class AbstractRules implements RulesInterface
{
    private $configuration;
    
    public function loadConfig(\Melete\Business\ConfigurationProvider $configProvider) {
        $this->configuration = $configProvider;
    }
    
    public function getConfig() {
        return $this->configuration;
    }
    
    abstract public function getConfigValue();
}

?>
