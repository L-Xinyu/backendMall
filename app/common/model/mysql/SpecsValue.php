<?php
/**
 * create by XinyuLi
 * @since 11/06/2020 22:25
 */

namespace app\common\model\mysql;
use think\Model;

class SpecsValue extends ModelBase
{
    protected $autoWriteTimestamp = true;

    public function getNormalBySpecsId($specdId, $field = '*'){
        $where = [
            'specs_id' => $specdId,
            'status' => config('status.mysql.table_normal'),
        ];

        $res = $this->where($where)
            ->field($field)
            ->select();
        return $res;
    }

}