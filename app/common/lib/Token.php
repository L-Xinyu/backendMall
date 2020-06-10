<?php
/**
 * create by XinyuLi
 * @since 10/06/2020 16:16
 */

namespace app\common\lib;

class Token
{
    /**
     * create token
     * @param $string
     * @return string
     */
    public static function getLoginToken($string) {
        //Generate a string that will not repeat
        $str = md5(uniqid(md5(microtime(true)), true));
        $token = sha1($str.$string);
        return $token;
    }
}