<?php
/**
 * create by XinyuLi
 * @since 12/06/2020 14:31
 */

namespace app\admin\controller;
use think\facade\Filesystem;

class Image extends AdminBase
{
    //上传图片
    public function upload(){
        if (!$this->request->isPost()){
            return show(config('status.error'),'The request is illegal');
        }
        $file = $this->request->file('file');
        //$filename = Filesystem::putFile('upload',$file);
        //create upload file at public
        $filename = Filesystem::disk('public')->putFile('image',$file);
        if (!$filename){
            return show(config('status.error'),'Failed to upload img');
        }
        $imageUrl = [
            'image' => '/upload/'.$filename
        ];
        return show(config('status.success'),'Success to upload img',$imageUrl);
    }

    //编辑器内上传图片
    public function layUpload(){
        if (!$this->request->isPost()){
            return show(config('status.error'),'The request is illegal!');
        }
        $file = $this->request->file('file');

        $filename = Filesystem::disk('public')->putFile('image',$file);
        if (!$filename){
            return json(['code' => 1,'data' => []],-1000);
        }
        //success status
        $result = [
            'code' => 0,
            'data' => [
                'src' => '/upload/'.$filename
            ],
        ];
        return json($result,200);
    }
}