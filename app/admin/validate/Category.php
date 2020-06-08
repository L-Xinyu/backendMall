<?php
/**
 * create by XinyuLi
 * @since 08/06/2020 19:41
 */

namespace app\admin\validate;
use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'name' => 'require|unique:category',
        'pid' => 'require',
    ];

    protected $message = [
        'name' => 'Must have a category name',
        'pid' => 'Must have parent ID',
        'name.unique' =>'Category name must be unique',
    ];
}