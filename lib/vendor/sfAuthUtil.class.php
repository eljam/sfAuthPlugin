<?php

class sfAuthUtil
{

    static function getHashedPasswordBySaltAndString($salt, $string)
    {
        $hash_type          = sfConfig::get('app_sf_auth_plugin_hash_type', 'sha256');
        $project_salt       = sfConfig::get('app_sf_auth_plugin_salt', 'something');
        
        return hash($hash_type, $salt . $string . $project_salt);
    }
}