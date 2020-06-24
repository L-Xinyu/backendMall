<?php
/**
 * create by XinyuLi
 * @since 17/06/2020 15:01
 */

namespace app\common\model\mysql;


class Order extends ModelBase
{
    protected $hidden = ['id','logistics','update_time', 'end_time','close_time',
        'consignment_time','pay_time','status','logistics_order'];
}