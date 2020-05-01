<?php

namespace app\http\middleware;

class Check
{
    public function handle($request, \Closure $next)
    {
        //echo 123;
        return $next($request);
    }
}
