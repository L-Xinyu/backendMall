<?php
/**
 * create by XinyuLi
 * @since 13/06/2020 15:21
 */

namespace app\common\lib;

class Show
{
    public static function success($data = [], $message = 'OK'){
        $result = [
            'status' => config('status.success'),
            'message' => $message,
            'result' => $data,
        ];
        return json($result);
    }

    public static function error($message = 'error', $status = 0, $data = []){
        $result = [
            'status' => $status,
            'message' => $message,
            'result' => $data,
        ];
        return json($result);
    }
}