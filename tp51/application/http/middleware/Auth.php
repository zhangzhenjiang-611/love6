<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/29
 * Time: 10:04
 */

namespace app\http\middleware;


class Auth
{
    public function handle($request, \Closure $next)
    {
        if ($request->param('id') == 10) {
            echo '管理员';
        }
        return $next($request);
    }

}