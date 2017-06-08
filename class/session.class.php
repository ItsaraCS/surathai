<?php
session_start();

/*for support PHP 5.4 or above*/
if(!function_exists("session_is_registered")){
        function session_register(){
        $args = func_get_args();
        foreach ($args as $key){
                $_SESSION[$key]=$GLOBALS[$key];
        }
    }
    function session_is_registered($key){
        return isset($_SESSION[$key]);
    }
    function session_unregister($key){
            unset($_SESSION[$key]);
    }
}
?>
