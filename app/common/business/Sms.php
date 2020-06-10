<?php
/**
 * create by XinyuLi
 * @since 09/06/2020 22:02
 */

declare(strict_types=1);
namespace app\common\business;
use app\common\lib\sms\AliSms;
use app\common\lib\Num;
class Sms
{
    public static function sendCode(string $phoneNumber,int $len) :bool {
        //code 4位
        $code = Num::getCode($len);
        $sms = AliSms::sendCode($phoneNumber,$code);
        if ($sms){
            //记录code到redis，并有失效时间
            cache(config('redis.code_pre').$phoneNumber,$code,config('redis.code_expire'));
        }
        return $sms;
    }
}