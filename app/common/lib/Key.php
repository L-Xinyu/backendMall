<?php
/**
 * create by XinyuLi
 * @since 15/06/2020 16:44
 */

namespace app\common\lib;


class Key
{
    /**
     * userCart user redis key
     * @param $userId
     * @return string
     */
    public static function userCart($userId){
        return config('redis.cart_pre') . $userId;
    }
}