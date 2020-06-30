<?php
/**
 * create by XinyuLi
 * @since 30/06/2020 12:50
 */

namespace app\api\controller\mall;
use app\api\controller\ApiBase;
use app\common\business\Goods as GoodsBusiness;
use app\common\lib\Show;

class Recommend extends ApiBase
{
    //Detail page show new Goods Recommend
    public function index($count=4){
        $newGoods = (new GoodsBusiness())->newGoodsRecommend($count);
        if (!$newGoods){
            return Show::error('Error displaying the latest product!');
        }
        return Show::success($newGoods);
    }
}