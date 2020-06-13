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

    //获取大图
    public function getNormalGoodsByCondition($where,$field = true,$limit = 5){
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $where['status'] = config('status.success');
        $result = $this->where($where)
            ->order($order)
            ->field($field)
            ->limit($limit)
            ->select();
        return $result;
    }

    //Image url
    public function getImageAttr($value){
        return request()->domain().$value;
    }

    //Home goods recommend
    public function getNormalGoodsFindInSetCategoryId($categoryId, $field = true, $limit = 5){
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        //whereFindInSet获取某分类下的数据
        $result = $this-> whereFindInSet('category_path_id',$categoryId)
            ->where('status','=',config('status.success'))
            ->order($order)
            ->field($field)
            ->limit($limit)
            ->select();
        //echo $this->getLastSql();exit;
        return $result;
    }

    //goods展示页栏目
    public function getNormalLists($data, $num = 5, $field = true,$order) {
        $res = $this;
        if(isset($data['category_path_id'])) {
            $res = $this->whereFindInSet("category_path_id", $data['category_path_id']);
        }
        $list = $res->where("status", "=", config("status.mysql.table_normal"))
            ->order($order)
            ->field($field)
            ->paginate($num);

        //echo $this->getLastSql();exit;
        return $list;
    }
}