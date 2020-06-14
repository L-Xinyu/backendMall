<?php
/**
 * create by XinyuLi
 * @since 13/06/2020 18:22
 */

namespace app\api\controller\mall;
use app\api\controller\ApiBase;
use app\common\business\Goods as GoodsBusiness;
use app\common\lib\Show;

class Detail extends ApiBase
{
    //goods detail
    public function index()
    {
        $id = input('param.id',0,'intval');
        if (!$id){
            return Show::error();
        }
        $result = (new GoodsBusiness())->getGoodsDetailBySkuId($id);
        if (!$result){
            return Show::error();
        }
        return Show::success($result);
    }
}