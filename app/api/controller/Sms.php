<?php
/**
 * create by XinyuLi
 * @since 09/06/2020 21:58
 */

declare(strict_types=1);
namespace app\api\controller;
use app\BaseController;

class Sms extends BaseController
{
    public function code() :object{
        return show(config('status.success'),'OK');
    }
}