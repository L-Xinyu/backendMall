<?php
/**
 * create by XinyuLi
 * @since 09/06/2020 23:56
 */

namespace app\api\validate;
use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username' => 'require',
        'phone_number' => 'require',
    ];
    protected $message = [
        'username' => 'Must have username',
        'phone_number' => 'Must have phone number',
    ];

    protected $scene = [
        'send_code' => ['phone_number'],
    ];
}