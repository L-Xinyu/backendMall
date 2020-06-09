<?php
/**
 * create by XinyuLi
 * @since 09/06/2020 15:27
 */

namespace app\common\lib;

class Status
{
    public static function getTableStatus(){
        $mysqlStatus = config('status.mysql');
        return array_values($mysqlStatus);
    }
}