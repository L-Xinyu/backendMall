<?php
/**
 * create by XinyuLi
 * @since 13/06/2020 18:22
 */

namespace app\api\controller\mall;
use app\api\controller\ApiBase;
use app\common\business\Goods as GoodsBusiness;
use app\common\lib\Show;

class Lists extends ApiBase
{
    //goods search
    public function index()
    {
        $pageSize = input("param.page_size", 10, "intval");
        $categoryId = input("param.category_id", 0, "intval");
        if (!$categoryId) {
            return Show::success();
        }
        $data = [
            "category_path_id" => $categoryId,
        ];
        //价格排序
        $field = input("param.field", "listorder", "trim");
        $order = input("param.order", 2, "intval");
        $order = $order == 2 ? "desc" : "asc";
        $order = [$field => $order];

        $goods = (new GoodsBusiness())->getNormalLists($data, $pageSize, $order);
        return Show::success($goods);
    }
}