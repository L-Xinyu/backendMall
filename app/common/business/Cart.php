<?php
/**
 * create by XinyuLi
 * @since 15/06/2020 15:49
 */

namespace app\common\business;

use app\common\lib\Key;
use think\Exception;
use think\facade\Cache;
use app\common\business\GoodsSku as GoodsSkuBusiness;

class Cart extends BusinessBase
{
    /**
     * 获取购物车信息并写入Redis
     * @param $userId
     * @param $id
     * @param $num
     * @return bool
     */
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

    /**
     * get shoppingCart lists
     * @param $userId
     * @return array
     */
    public function lists($userId){
        try {
            $res = Cache::hGetAll(Key::userCart($userId));
        }catch (\Exception $e){
            return [];
        }
        if (!$res){ return [];}

        $result = [];
        $skuIds = array_keys($res);
        //get data in table sku
        $skus = (new GoodsSkuBusiness())->getNormalInIds($skuIds);
        $skuIdPrice = array_column($skus,'price','id');
        $skuIdSpecsValueIds = array_column($skus,'specs_value_ids','id');
        //key:sku表中的主键id,value:规格属性组装数据
        $specsValues = (new SpecsValue())->detailSpecsValue($skuIdSpecsValueIds);
        //dump($res);exit;

        foreach ($res as $k=>$v){
            $v = json_decode($v,true);
            $v['id'] = $k;
            $v['image'] = preg_match("/http:\/\//",$v['image'])?$v['image']:request()->domain().$v['image'];
            $v['price'] = $skuIdPrice[$k] ?? 0;
            $v['sku'] = $specsValues[$k] ?? "暂无规格";
            $result[] = $v;
        }
        return $result;
    }

    /**
     * Delete shoppingCart goods
     * @param $userId
     * @param $id
     * @return bool
     */
    public function deleteRedis($userId, $id){
        try {
            $res = Cache::hDel(Key::userCart($userId), $id);
        }catch (\Exception $e){
            return FALSE;
        }
        return $res;
    }

    /**
     * update goods num at cart
     * @param $userId
     * @param $id
     * @param $num
     * @return bool
     * @throws Exception
     */
    public function updateRedis($userId,  $id, $num) {
        try {
            $get = Cache::hGet(Key::userCart($userId), $id);
        }catch (\Exception $e) {
            return FALSE;
        }
        if($get) {
            $get = json_decode($get, true);
            $get['num'] = $num;
        } else {
            throw new Exception('The product does not exist in the shopping cart!!!');
        }
        try {
            $res = Cache::hSet(Key::userCart($userId), $id, json_encode($get));
        }catch (\Exception $e) {
            return FALSE;
        }
        return $res;
    }
}