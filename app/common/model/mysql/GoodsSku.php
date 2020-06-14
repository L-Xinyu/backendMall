<?php
/**
 * create by XinyuLi
 * @since 12/06/2020 17:07
 */

namespace app\common\model\mysql;
use think\Model;

class GoodsSku extends ModelBase
{
    public function goods(){
        return $this->hasOne(Goods::class,'id','goods_id');
    }
}