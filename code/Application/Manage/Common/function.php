<?php
/*
     * @desc 验证参数
     * @param $rules array('参数名', 'a.是否必填(1|0)','b.正则表达式','a不满足提示', 'b不满足提示')
     */
    function validParam($rules){
        $error = '';
        $code = '';
        $in = array();
        foreach ($rules as $r)
        {   
            $param = I('post.'.$r[0]);
        
           // if(empty($param)){
            if($param==''){
                $param = I('get.'.$r[0]);
            }

            //是否必填
            if($r[1] == 1)
            {
                if($param == '')
                {
                    $error = $r[3] ? $r[3] : '参数'.$r[0].'不能为空';
                    $code = 407;
                    break;
                }
            }
            //是否符合正则表达式
            if($param != '')
            {
                if($r[2] != '' && !preg_match($r[2],$param))
                {
                    $error = $r[4] ? $r[4] : '参数'.$r[0].'不合法'; 
                    $code = 408; 
                    break;
                }
                $in[$r[0]] = $param;
            }
        }
        if($error)
            err_result($code, $error);
        else
            return $in;
    }


        /*
     * @desc 返回错误
     */
    function err_result($code, $msg, $data = array())
    {
        $result = array(
            "code" => $code,
            "msg" => $msg,
            "data" => $data
        );
        ajaxReturn($result,"JSON");
    }



    /*
     * @desc 记录请求日志
     */
    function ajaxReturn($data,$dataType,$json_option=0)
    {   
        \Think\Log::write("\r\n时间:".date("Y-m-d H:i:s")."\r\nIP:".$_SERVER['REMOTE_ADDR']."\r\n方法:".MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME."\r\n参数:".var_export(I('post.'),true)."\r\n返回:".var_export($data,true),'INFO');
    }