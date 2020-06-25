<?php
/**
 * create by XinyuLi
 * @since 25/06/2020 15:06
 */

namespace app\common\business;
use app\common\model\mysql\UserAddress as UserAddressModel;
use think\Exception;

class UserAddress extends BusinessBase
{
    public $model = null;
    public function __construct(){
        $this->model = new UserAddressModel();
    }

    public function addAddress($data){
        try {
            $result = $this->model->save($data);
        }catch (\Exception $e){
            throw new Exception('Failed to insert data!');
        }
        return $result;
    }

    public function getAddress($data){
        $condition = [
            'user_id' => $data['user_id']
        ];
        try {
            $userAddress = $this->model->getAddressByUserID($condition);
        }catch (\Exception $e){
            $userAddress = [];
        }
        if (!$userAddress){
            return [];
        }
        //dump($userAddress);exit;
        $address = $userAddress->toArray();
        if (empty($address)){
            return [];
        }
        return $address;
    }
}