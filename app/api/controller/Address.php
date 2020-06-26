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
    public function add(){
        if (!$this->request->isPost()){
            return Show::error();
        }

        $name = input('param.name','','trim');
        $phone_number = input('param.phone_number','','trim');
        $consigneeInfo = input('param.consignee_info','','trim');
        $isDefault = input('param.is_default','','intval');
        if (!$name || !$consigneeInfo){
            return Show::error('Parameter is invalid...');
        }

        $data = [
            'name' =>$name,
            'phone_number' => $phone_number,
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

    public function read(){
        $condition = [
            'user_id' => $this->userId,
        ];
        $result = (new UserAddressBusiness())->getAddress($condition);

        if (!$result){
            return Show::error('Error getting user address information!');
        }
        return Show::success($result);
    }
}