<?php
/**
 * create by XinyuLi
 * @since 25/06/2020 15:33
 */

namespace app\api\controller;
use app\common\lib\Show;
use app\common\business\UserAddress as UserAddressBusiness;

class Address extends AuthBase
{
    public function addAddress(){
        if (!$this->request->isPost()){
            return Show::error();
        }

        $consigneeInfo = input('param.consignee_info','','trim');
        $isDefault = input('param.is_default','','intval');
        if (!$consigneeInfo){
            return Show::error('Parameter is invalid...');
        }

        $data = [
            'consignee_info' => $consigneeInfo,
            'is_default' => $isDefault,
            'user_id' => $this->userId,
        ];

        try {
            $result = (new UserAddressBusiness())->addAddress($data);
        }catch (\Exception $e){
            return Show::error($e->getMessage());
        }
        if (!$result){
            return Show::error('Failed to add new address...');
        }
        return Show::success($result);
    }
}