<?php
/**
 * create by XinyuLi
 * @since 15/06/2020 15:49
 */

namespace app\common\business;

use app\common\lib\Key;
use think\facade\Cache;

class Cart extends BusinessBase
{
    public function insertRedis($userId, $id, $num){
        //sku_id get goods data
        $goodsSku = (new GoodsSku())->getNormalSkuAndGoods($id);
        if (!$goodsSku){
            return FALSE;
        }
        //dump($goodsSku);exit;
        $data = [
            'title' => $goodsSku['goods']['title'],
            'image' => $goodsSku['goods']['recommend_image'],
            'num' => $num,
            'goods_id' => $goodsSku['goods']['id'],
            'create_time' => time()
        ];
        try {
            $get = Cache::hGet(Key::userCart($userId), $id);
            if ($get){
                //对json解码 拿到num值 进行商品数量的累加
                $get = json_decode($get,true);
                $data['num'] = $data['num'] + $get['num'];
            }
            $res = Cache::hSet(Key::userCart($userId), $id, json_encode($data));
        }catch (\Exception $e){
            return FALSE;
        }
        return $res;
    }
}