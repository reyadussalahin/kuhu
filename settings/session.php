<?php

/**
 * session parameters to use with "ini_set(, )"
 * and "session_set_cookie_params(, , , ....)"
 */
class Session {
    /**
     * write here session ini settings 
     * 
     */

    // private static $name = "";
    // private static $value = "";
    // private static $save_path = "";
    // private static $session.cookie_lifetime = ?;
    // private static $session.use_cookies = ?;
    // private static $session.use_only_cookies = ?;
    // private static $session.use_strict_mode = ?;
    // private static $session.gc_maxlifetime = ?;
    // private static $session.use_trans_sid = ?;
    // private static $session.trans_sid_tags = ?;
    // private static $session.hash_function = ?;
    // etc. etc....there's still a lot of parameters for session settings
    // for others check php.ini file

    /**
     * settings to use with "session_set_cookie_params()"
     */
    private static $lifetime = 60 * 60 * 24; // setting session period 1 day or 24 hours
    // private static $path = "";
    // private static $domain = "";
    // private static $secure = "";
    // private static $httponly = "";


    public static function getLifetime() {
        return self::$lifetime;
    }
}

?>