<?php
/**
 * create by XinyuLi
 * @since 10/06/2020 16:32
 */

namespace app\common\lib;

class Time
{
    //登录保存天数type=1 7天 type=2 30天
    public static function userLoginExpiresTime($type = 2){
        $type = !in_array($type,[1,2]) ? 2 : $type;
        if ($type == 1){
            $day = $type * 7;
        }elseif ($type == 2){
            $day = $type * 30;
        }
        return $day * 24 * 3600;
    }
}