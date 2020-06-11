<?php
/**
 * create by XinyuLi
 * @since 11/06/2020 22:20
 */

namespace app\common\business;
use app\common\model\mysql\SpecsValue as SpecsValueModel;

class SpecsValue extends BusinessBase
{
    public $model = NULL;
    public function __construct(){
        $this->model = new SpecsValueModel();
    }

    /**
     * getBySpecsId
     * @param $specsId
     * @return array
     */
    public function getBySpecsId($specsId){
        try {
            $result = $this->model->getNormalBySpecsId($specsId, "id, name");
        }catch (\Exception $e) {
            return [];
        }
        $result = $result->toArray();
        return $result;
    }

}