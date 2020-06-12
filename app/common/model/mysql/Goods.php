<?php
/**
 * create by XinyuLi
 * @since 06/06/2020 16:18
 */

namespace app\common\model\mysql;

class Goods extends ModelBase
{
    //获取后端列表数据
    public function getLists($data, $num = 10) {
        $order = ["listorder" => "desc", "id" => "desc"];
        $list = $this->whereIn("status", [0, 1]) //取status为0、1的数据
            ->order($order)
            ->paginate($num);
        return $list;
    }
}