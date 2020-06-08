<?php
/**
 * create by XinyuLi
 * @since 06/06/2020 13:59
 */

namespace app\controller;


class Error
{
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        $result = [
            "status" => config("status.controller_not_found"),
            "message" => "cannot find controller",
            "result" => null
        ];
        return json($result,400);
    }
}