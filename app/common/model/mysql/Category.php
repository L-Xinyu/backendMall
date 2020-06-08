<?php
/**
 * create by XinyuLi
 * @since 08/06/2020 20:03
 */

namespace app\common\model\mysql;
use think\Model;

class Category extends Model
{
    protected $autoWriteTimestamp = true;
    //Get category information
    public function getNormalCategories($field = "*"){
        $where = [
            'status' => config('status.mysql.table_normal'),
        ];
        $result = $this->where($where)->field($field)->select();
        return $result;
    }
}