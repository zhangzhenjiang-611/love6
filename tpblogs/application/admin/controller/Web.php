<?php

namespace app\admin\controller;

use think\Controller;

class Web extends Base
{
    //
    public function index(){

        $url ='http://192.168.1.218:11289/KWSService.asmx?wsdl';
        WebService($url,'Web','app\admin\controller');
    }
    public function itemType( $type='', $style='' )
    {
        echo $type.$style;
    }
}
