<?php

class CSRF
{
    public static function create_token()
    {
        $token = md5(time());
        $_SESSION['token'] = $token;

        echo "<input name='token' value='$token' type= 'hidden'>";
    }


}