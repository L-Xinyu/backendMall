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

    /**
     * Get Sku
     * @param $gids
     * @param $flagValue
     * @return array
     */
    public function detailGoodsSkus($gids, $flagValue){
       $specsValueKeys = array_keys($gids);
       foreach ($specsValueKeys as $specsValueKey){
           $specsValueKey = explode(',',$specsValueKey);
           foreach ($specsValueKey as $k=>$v){
               $new[$k][] = $v;
               $specsValueIds[] = $v;
           }
       }
       $specsValueIds = array_unique($specsValueIds);
       $specsValues =  $this->getNormalInIds($specsValueIds);

       $flagValue = explode(',',$flagValue);
       $result = [];
       foreach ($new as $key => $newValue){
           $newValue = array_unique($newValue);
           $list = [];
           foreach ($newValue as $vv){
               $list[] = [
                   'id' => $vv,
                   'name' => $specsValues[$vv]['name'],
                   'flag' => in_array($vv,$flagValue)?1:0,
               ];
           }
           $result[$key] = [
               'name' => $specsValues[$newValue[0]]['specs_name'],
               'list' => $list,
           ];
       }
//       dump($result);exit;
       return $result;
    }

    public function getNormalInIds($ids){
        if (!$ids){
            return [];
        }
        try {
            $result = $this->model->getNormalInIds($ids);
        }catch (\Exception $e){
            return [];
        }
        $result = $result->toArray();
//        dump($result);exit;
        if (!$result){
            return [];
        }
        //拿到规格属性数据
        $specsNames = config('specs');
        $specsNamesArrs = array_column($specsNames,'name','id');

        $res = [];
        foreach ($result as $resultValue) {
            $res[$resultValue['id']] = [
                'name' => $resultValue['name'],
                'specs_name' => $specsNamesArrs[$resultValue['specs_id']] ?? '',
            ];
        }
        return $res;
    }
}