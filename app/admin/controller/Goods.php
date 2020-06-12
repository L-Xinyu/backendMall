<?php
/**
 * create by XinyuLi
 * @since 11/06/2020 17:23
 */

namespace app\admin\controller;
use app\common\business\Goods as GoodsBusiness;
class Goods extends AdminBase
{
    public function index(){
        return view();
    }

    public function add(){
        return view();
    }

    public function save() {
        // 判断是否为post请求， 也可以通过在路由中做配置支持post
        if(!$this->request->isPost()) {
            return show(config('status.error'), "参数不合法");
        }
        //validate

        $data = input("param.");
//        $check = $this->request->checkToken('__token__');
//        if(!$check) {
//            return show(config('status.error'), "非法请求");
//        }
        $data['category_path_id'] = $data['category_id'];
        $result = explode(",", $data['category_path_id']);
        //拿到category_path_id数组中最后一个值为category_id
        $data['category_id'] = end($result);

        $res = (new GoodsBusiness())->insertData($data);
        if(!$res) {
            return show(config('status.error'), "Failed to add Goods");
        }
        return show(config('status.success'), "Success to add Goods!");
    }
}