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
    public function getRotationChart(){
        $result = (new GoodsBusiness)->getRotationChart();
        return Show::success($result);
    }
}