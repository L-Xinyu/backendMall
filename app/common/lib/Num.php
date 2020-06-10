<?php
/**
 * 记录和验证码等数字相关
 * create by XinyuLi
 * @since 10/06/2020 12:55
 */

declare(strict_types = 1);
namespace app\common\lib;

class Num
{
    public static function getCode(int $len = 4) :int {
        $code = rand(1000,9999);
        if ($len = 6){
            $code = rand(100000,999999);
        }
        return $code;
    }
}