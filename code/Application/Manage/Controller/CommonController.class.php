<?php
namespace Manage\Controller;
use Think\Controller;
//引用phpexcel
use PHPExcel;
use PHPExcel_IOFactory;
use Org\Util\Rbac;
class CommonController extends Controller {
    public function _initialize(){

        //判断是否登陆 
         // if(!isset($_SESSION[C('USER_AUTH_KEY')])){
         //     //echo "没有登陆";
         //     $rel['code'] = '2';
         //     $rel['msg'] = '过期登陆';
         //     $this->ajaxReturn($rel,'JSON');
         //    // $this->redirect('manage/login/index');
         // }
         //RBAC认证
         //判断当前模块是否在无需认证的模块里 
        $notAuth=in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE'))) || in_array(ACTION_NAME,explode(',',C('NOT_AUTH_ACTION')));
         if(C('USER_AUTH_ON') && !$notAuth){
             //$t1 = new \Org\Util\Rbac();
             //检测用户是否有相应的权限
            // dump(Rbac::AccessDecision());
            // dump(88);
             // if(!Rbac::AccessDecision()){
             //   dump(MODULE_NAME);
             //    dump(CONTROLLER_NAME);
             //    dump(ACTION_NAME);
             //    dump($_SESSION);
             // dump(111);exit;
             //    $rel['code'] = '4';
             //    $rel['msg'] = '没有权限';
             //    $this->ajaxReturn($rel,'JSON');
               
               
             // }
         }
    }

   

    /*
     * @desc 记录请求日志
     */
    protected function ajaxReturn($data,$dataType,$json_option=0)
    {   
        \Think\Log::write("\r\n时间:".date("Y-m-d H:i:s")."\r\nIP:".$_SERVER['REMOTE_ADDR']."\r\n方法:".MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME."\r\n参数:".var_export(I('post.'),true)."\r\n返回:".var_export($data,true),'INFO');
        parent::ajaxReturn($data,$dataType);
    }
    /*
     * @desc 返回错误
     */
    protected function err_result($code, $msg, $data = array())
    {
        $result = array(
            "code" => $code,
            "msg" => $msg,
            "data" => $data
        );
        $this->ajaxReturn($result,"JSON");
    }

     /*
     * @desc 验证参数
     * @param $rules array('参数名', 'a.是否必填(1|0)','b.正则表达式','a不满足提示', 'b不满足提示')
     */
    protected function validParam($rules)
    {
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
            $this->err_result($code, $error);
        else
            return $in;
    }


    /**
     * 期间日期
     * @param $startDate
     * @param $endDate
     * @return array
     */
    protected function periodDate($startDate, $endDate){
        $startTime = strtotime($startDate);
        $endTime = strtotime($endDate);
        $arr = array();
        while ($startTime <= $endTime){
            $arr[] = date('Y-m-d', $startTime);
            $startTime = strtotime('+1 day', $startTime);
        }
        return $arr;
    }

    protected  function export($list,$title_array,$excel_filename){
        //excel版本 
        if(empty($type)){ 
          $type='07';
        }
        //导入第三方类库
        vendor('PHPExcel.PHPExcel');
        //实例化PHPExcel这个类，相当于在桌面新建了一个Excel的文件
        $PHPExcel = new \PHPExcel();
        //获取当前活动的sheet
        $PHPSheet = $PHPExcel->getActiveSheet();
        //给当前活动的sheet重命名
        $PHPSheet -> setTitle($excel_filename);
        //添加表头
        $code = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');    
        //第一行设置内容
        $PHPSheet->setCellValue('A1',$excel_filename);
        //合并
        $PHPSheet->mergeCells('A1:M1');
        //设置单元格内容加粗
        $PHPSheet->getStyle('A1')->getFont()->setBold(true);
        //设置单元格内容水平居中
        $PHPSheet->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // 设置第一行和第一行的行高
        $PHPSheet->getRowDimension('1')->setRowHeight(40);
        //设置字体大小
        $PHPSheet->getStyle('A1')->getFont()->setSize(20);
       // $title_array = array('自助机编号','卡类型','名字');
        foreach($title_array as $key=>$value){
          $PHPSheet -> setCellValue($code[$key].'2',$value);
        }
        //添加内容
        for($i=3;$i<=count($list)+2;$i++){
          $j=0;
          foreach($list[$i-3] as $key=>$val){
              $PHPSheet -> setCellValue($code[$j].$i,$val);
              $j++;  
          }
        }
        $filename=date('YmdHis');
        if($type == '07'){
          //Excel2007 版本   03版本 Excel5
          $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');
          //告诉浏览器要输出的文件
          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          //文件的名称
          header('Content-Disposition: attachment;filename="'.$excel_filename.$filename.'.xlsx"');
        }elseif($type == '03'){
          $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel5');
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename="'.$excel_filename.$filename.'.xls"');
        }  
        //禁止缓存
        header("Cache-Control:max-age=0");
        //输出
        $PHPWriter ->save('php://output');
      }


}

 
