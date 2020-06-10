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
        'code' => 'require|number|min:4',
        'type' => ['require','in'=>'1,2'],
        'sex' => ["require", "in"=>"0,1,2"],
    ];
    protected $message = [
        'username' => 'Must have username',
        'phone_number' => 'Must have phone number',
        'code.require' => 'Need message code',
        'code.number'  =>  'MessageCode must be number',
        'code.min'  =>  'Verification code length must be greater than 4',
        'type.require' => 'Need type',
        'type.in' => 'Wrong type value',
        'sex.require' => 'Must have sex',
        'sex.in' => 'Wrong sex value'
    ];

    protected $scene = [
        'send_code' => ['phone_number'],
        'login' => ['phone_number', 'code', 'type'],
        'update_user' => ['username', 'sex'],
    ];
}