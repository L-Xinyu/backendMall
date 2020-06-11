<?php
/**
 * create by XinyuLi
 * @since 11/06/2020 22:20
 */

namespace app\common\business;

class BusinessBase
{
    public function add($data){
        $data['status'] = config('status.mysql.table_normal');
        try {
            $this->model->save($data);
        }catch (\Exception $e){
            return 0;
        }
        return $this->model->id;
    }

}