<?php
/**
 * create by XinyuLi
 * @since 07/06/2020 16:02
 */
declare(strict_types = 1);
namespace app\admin\middleware;

class Auth
{
    public function handle($request,\Closure $next){

        if (empty(session(config('admin.session_admin'))&&!preg_match("/login/",$request->pathinfo()))){
            //return redirect((string)url('login/index'));
        }
        $response = $next($request);
        return $response;
    }

    public function end(\think\Response $response){

    }
}