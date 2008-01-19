<?php

require_once 'HTMLPurifier/Strategy.php';
require_once 'HTMLPurifier/HTMLDefinition.php';
require_once 'HTMLPurifier/IDAccumulator.php';

require_once 'HTMLPurifier/AttrValidator.php';

/**
 * Validate all attributes in the tokens.
 */

class HTMLPurifier_Strategy_ValidateAttributes extends HTMLPurifier_Strategy
{
    
    public function execute($tokens, $config, $context) {
        
        // setup validator
        $validator = new HTMLPurifier_AttrValidator();
        
        $token = false;
        $context->register('CurrentToken', $token);
        
        foreach ($tokens as $key => $token) {
            
            // only process tokens that have attributes,
            //   namely start and empty tags
            if (!$token instanceof HTMLPurifier_Token_Start && !$token instanceof HTMLPurifier_Token_Empty) continue;
            
            // skip tokens that are armored
            if (!empty($token->armor['ValidateAttributes'])) continue;
            
            // note that we have no facilities here for removing tokens
            $validator->validateToken($token, $config, $context);
            
            $tokens[$key] = $token; // for PHP 4
        }
        $context->destroy('CurrentToken');
        
        return $tokens;
    }
    
}

