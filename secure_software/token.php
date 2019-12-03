<?php
class Token{
    public static function generate(){
        return $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
    }

    public static function check($token){
         if(isset($_SESSION['token']) && $token === $_SESSION['token']){
             unset($_SESSION['token']);
             return true;
         }
         return false;
    }

    //This function will automatically time a user out if they have been logged in for an hour
    public static function sessionCheck(){
        if(isset($_SESSION['logged_in'])){
            $expire_after = 10;
            $inactivity = time() - $_SESSION['logged_in'];
            $expire = $expire_after * 60;
        
            if($inactivity>=$expire){
                session_start();
                session_unset();
                session_destroy();
                header("Location: index.php");
            }
        }
        $_SESSION['logged_in'] = time();
        
    }
}


?>