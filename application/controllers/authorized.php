<?php

class Authorized
{
    private static $authorized_user = array('nickwu','zhenyangli','stevenyshi','wfhuang','v_ppcchen','v_qianyang','v_tyye','v_xuehguo','v_yxge');
    public static function IsAuthorized()
    {
        $user = $_COOKIE['PAS_COOKIE_USER'];
        
        if(in_array($user,self::$authorized_user))
        {
            return true;
        }
        else
        {
            return false;
        }

    }
}

?>
