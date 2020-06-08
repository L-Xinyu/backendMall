<?php
/**
 * create by XinyuLi
 * @since 06/06/2020 14:16
 */

namespace app\controller;
use app\BaseController;
use think\facade\Db;
use app\model\Product;

class Data extends BaseController
{
    public function index(){
        //门面模式
        //$result = Db::table("mall_product")->where("id",2)->find();

        //容器
        //$result = app("db")->table("mall_product")->where("id",2)->find();

        //数据排序
        $result = Db::table("mall_product")
            //->order('id','desc')
            //->limit(0,2)  //分页 从零开始的两个数据
            //->page(1,3)
            //获取id值>2的数据
            ->where([
                //['id','>',0],
                ['id','in','1,2,3'],
                ['stock','>',60]
            ])
            ->select();
            //->find();
        dump($result);
    }

    public function dataInsert(){
        $data = [
            'name' => 'ipad Air',
            'code' => 245323,
            'description' => 'new product ipad aire',
            'stock' => 60,
            'price' => 379,
        ];
        //add
        //$result = Db::table("mall_product")->insert($data);
        //delete
        //$result = Db::table('mall_product')->where('id',6)->delete();
        //update
        $result = Db::table('mall_product')->where('id',5)->update(['code'=>22222]);
        echo Db::getLastSql();
        dump($result);
    }

    public function product(){
        $result = Product::find(1);
        dump($result->toArray());
    }
}