<?php
/**
 * create by XinyuLi
 * @since 06/06/2020 16:18
 */

namespace app\common\model\mysql;

class Goods extends ModelBase
{
    /**
     * title&time to search goods
     * 调用withSearch方法的时触发
     * @param $query
     * @param $value
     */
    public function searchTitleAttr($query, $value) {
        $query->where('title', 'like', '%' . $value . '%');
    }

    public function searchCreateTimeAttr($query, $value) {
        $query->whereBetweenTime('create_time', $value[0], $value[1]);
    }

    //获取后端列表数据
    public function getLists($likeKeys, $data, $num = 10) {
        $order = ["listorder" => "desc", "id" => "desc"];
        //if key not empty,search goods
        if (!empty($likeKeys)){
            $res = $this->withSearch($likeKeys,$data);
        }else{
            $res = $this;
        }
        $list = $res->whereIn("status", [0, 1]) //取status为0、1的数据
            ->order($order)
            ->paginate($num);
        //echo $this->getLastSql();exit;
        return $list;
    }
}