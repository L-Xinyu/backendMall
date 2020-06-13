<?php
/**
 * create by XinyuLi
 * @since 13/06/2020 14:31
 */

namespace app\api\controller;
use app\common\business\Goods as GoodsBusiness;
use app\common\lib\Show;
class Index extends ApiBase
{
    //轮播图
    public function getRotationChart(){
        $result = (new GoodsBusiness)->getRotationChart();
        return Show::success($result);
    }

    //首页商品推荐
    public function cagegoryGoodsRecommend(){
        $categoryIds = [
            28,
            29
        ];
        $result = (new GoodsBusiness())->cagegoryGoodsRecommend($categoryIds);
        return Show::success($result);
    }
}