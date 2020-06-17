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
use app\common\lib\Arr;
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
     * @param $ids goods sku_id
     * @return array
     */
    public function lists($userId, $ids){
        try {
            if ($ids){
                $ids = explode(',',$ids);
                //Get a specific piece of data
                $res = Cache::hMget(Key::userCart($userId),$ids);
                //Order:判断是否为非法不存在数据
                if (in_array(false,array_values($res))){
                    return [];
                }
            }else{
                $res = Cache::hGetAll(Key::userCart($userId));
            }

        }catch (\Exception $e){
            return [];
        }
        if (!$res){ return [];}

        $result = [];
        $skuIds = array_keys($res);
        //get data in table sku
        $skus = (new GoodsSkuBusiness())->getNormalInIds($skuIds);
        //stock
        $stocks = array_column($skus,'stock','id');

        $skuIdPrice = array_column($skus,'price','id');
        $skuIdSpecsValueIds = array_column($skus,'specs_value_ids','id');
        //key:sku表中的主键id,value:规格属性组装数据
        $specsValues = (new SpecsValue())->detailSpecsValue($skuIdSpecsValueIds);
        //dump($res);exit;

        foreach ($res as $k=>$v){
            $price = $skuIdPrice[$k] ?? 0;
            $v = json_decode($v,true);
            //库存判断 $k->goods sku_id
            //当前库存数据$stocks[$k]<当前传递的good库存$v['num']
            if ($ids && isset($stocks[$k]) && $stocks[$k] < $v['num']){
                throw new Exception($v['title'].' Out of stock');
            }
            $v['id'] = $k;
            $v['image'] = preg_match("/http:\/\//",$v['image'])?$v['image']:request()->domain().$v['image'];
            $v['price'] = $price;
            $v['total_price'] = $price * $v['num'];
            $v['sku'] = $specsValues[$k] ?? "暂无规格";
            $result[] = $v;
        }
        //解决购物车中redis hash无序的问题
        if(!empty($result)){
            $result = Arr::arrsSortByKey($result,'create_time');
//            $resultSort = array_column($result,'create_time');
//            array_multisort($resultSort, SORT_DESC, $result);
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

    /**
     * Get the number of goods in the shopping cart
     * @param $userId
     * @return int
     */
    public function getCount($userId){
        try {
            $count = Cache::hLen(Key::userCart($userId));
        }catch (\Exception $e){
            return 0;
        }
        return intval($count);
    }
}