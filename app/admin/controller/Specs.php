<?php
/**
 * create by XinyuLi
 * @since 11/06/2020 17:25
 */

namespace app\admin\controller;

class Specs extends AdminBase
{
    public function dialog(){
        return view("",[
            'specs' => json_encode(config('specs'))
        ]);
    }
}