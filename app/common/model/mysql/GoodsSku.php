<?php
/**
 * create by XinyuLi
 * @since 12/06/2020 17:07
 */

namespace app\common\model\mysql;

class GoodsSku extends ModelBase
{
    public function goods(){
        return $this->hasOne(Goods::class,'id','goods_id');
    }

    public function getNormalByGoodsId($goodsId = 0){
        $where = [
            'goods_id' => $goodsId,
            'status' => config('status.mysql.table_normal'),
        ];
        return $this->where($where)->select();
    }
    //减库存
    public function incStock($id,$num){
        return $this->where('id','=',$id)
            ->inc('stock',$num)
            ->update();
    }
}