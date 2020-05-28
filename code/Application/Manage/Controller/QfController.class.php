<?php
namespace Manage\Controller;
use Think\Controller;
//引用phpexcel
use PHPExcel;
use PHPExcel_IOFactory;
class QfController extends Controller {
    public function index(){
		$this->display();
    }

    //对账,导出
    public function getDz(){
        $_POST['trade_no'] = '';  //订单号
        $_POST['card_type'] = 'all';  //卡类型 
        $_POST['business_type'] = 'all';    //业务类型
        $_POST['pay_type'] = 'all'; //支付方式
        $_POST['operator_id'] = 'all'; //操作员(自助机编号)
        $_POST['start'] = '';  
        $_POST['end'] = '';
        $_POST['sday'] = '2020-02-26';
        $_POST['export'] = '1';

        //excel版本 
        $_POST['type'] = '07';
        $type = I('post.type');

        if(empty($type)){ 
            $type='07';
        }
        $export = I("post.export");
        $alipay_table='payment';
        $his_table='payment123';
        $where="1=1";
        //订单号  
        if(I("post.trade_no")!=""){
            $where .=" and trade_no = '".I('post.trade_no')."'";
        }
        //卡类型
        if(I("post.card_type")!=""&&I("post.card_type")!="all"){
            $where .=" and card_type = '".I("post.card_type")."'";
        }
        //业务类型
        if(I("post.business_type")!=""&&I("post.business_type")!="all"){
            $where .=" and business_type = '".I("post.business_type")."'";
        }
        //支付方式
        if(I("post.pay_type")!=""&&I("post.pay_type")!="all"){
            $where .=" and pay_type = '".I("post.pay_type")."'";
        }
        //操作员
        if(I("post.operator_id")!="all"&&I("post.operator_id")!=""){
            $where .=" and zzj_code = '".I("post.operator_id")."'";
        }
        //按时间段查询
        if(I("post.start")!=""&&I("post.start")!="all"&&I("post.end")!=""&&I("post.end")!="all"){
            $start = I("post.start")." 00:00:01";
            $end = I("post.end")." 23:59:59";
            $where .=" and create_time>'".$start."' and create_time<'".$end."'";
        }
        //按天查询
        if(I("post.sday")!="all"&&I("post.sday")!=""){
            $where .=" and left(create_time,10)='".I("post.sday")."'";
        }
        $other_list = M($alipay_table)->where($where)->order("id desc")->select();
        $other_ary = M($alipay_table)->field("sum(personpay_fee) as tamount,count(id) as idnum")->where($where)->select();
 
        $hispay_list = M($his_table)->where($where)->order("id desc")->select();
        $hispay_ary = M($his_table)->field("sum(personpay_fee) as his_total_amount,count(id) as his_idnum")->where($where)->select();

        $list = array();
        $list['code']=0;
        $list['msg']="成功";
        //分别算出自助跟his的总笔数跟总金额 
        $list['data']['total_of']=array_merge($other_ary[0], $hispay_ary[0]);
      
        /**查his的单边账（自助无，his有）开始 */
        for($k=0;$k<count($other_list);$k++){
            $other_arry[]=$other_list[$k]['trade_no']; 
        }
        for($m=0;$m<count($hispay_list);$m++){
            if(!in_array($hispay_list[$m]['trade_no'], $other_arry)){        
              //HIS单边数据	 
              $list['data']['his_abnormal_data'][$m]['zzj'] = "";
              $list['data']['his_abnormal_data'][$m]['his_no'] = $hispay_list[$m]['trade_no'];
              $list['data']['his_abnormal_data'][$m]['his_fee'] = $hispay_list[$m]['personpay_fee'];
              $list['data']['his_abnormal_data'][$m]['his_fee_date'] = $hispay_list[$m]['pay_time'];
              
            }
        }
        /**查his的单边账（自助无，his有）结束 */ 
 
        /**查自助的单边账（自助有，his无）开始 */
        for($t=0;$t<count($hispay_list);$t++){
            $his_arry[]=$hispay_list[$t]['trade_no']; 
        }
        for($f=0;$f<count($other_list);$f++){
            if(!in_array($other_list[$f]['trade_no'], $his_arry)){     
              $list['data']['zzj_abnormal_data'][$f]['his'] = "";   
              $list['data']['zzj_abnormal_data'][$f]['trade_no'] = $other_list[$f]['trade_no'];
              $list['data']['zzj_abnormal_data'][$f]['other_fee'] = $other_list[$f]['personpay_fee'];
              $list['data']['zzj_abnormal_data'][$f]['pay_time'] = $other_list[$f]['pay_time'];
              
            }
        }
        /**查自助的单边账（自助有，his无）结束 */ 
        
        for($i=0;$i<count($other_list);$i++){
            if($other_list[$i]['card_type']=='1'){
                //患者卡类型:1社保卡2京医通卡3身份证
                $card_type = '社保卡';
            }elseif($other_list[$i]['card_type']=='2'){
                $card_type = '京医通卡';
            }elseif($other_list[$i]['card_type']=='3'){
                $card_type = '身份证';
            }
            if($other_list[$i]['card_type']=='1'){
                //支付方式:1支付宝2微信3银行卡4京医通(卡余额支付)
                $pay_type = '支付宝';
            }elseif($other_list[$i]['card_type']=='2'){
                $pay_type = '微信';
            }elseif($other_list[$i]['card_type']=='3'){
                $pay_type = '银行卡';
            }elseif($other_list[$i]['card_type']=='4'){
                $pay_type = '京医通(卡余额支付)';
            }
            $sql="SELECT name FROM `ss_module` where `index` ='".$other_list[$i]['business_type']."'";
            $rel_module = M('module')->query($sql);

            $his_have = 0;
            //查单边账 
            for($m=0;$m<count($hispay_list);$m++){ 
               if($hispay_list[$m]['trade_no'] == $other_list[$i]['trade_no']){  
                                  
                    $his_have=1;
                    $his_no = $hispay_list[$m]['trade_no'];
                    $his_fee = $hispay_list[$m]['personpay_fee'];
                    $his_fee_date = date("Y-m-d",strtotime($hispay_list[$m]['create_time']));
                   
                
                    if($other_list[$i]['personpay_fee']-$hispay_list[$m]['personpay_fee']!=0){
                        $his_have=0;
                         //单边账 
                        $list['data']['abnormal_data'][$i]['abnormal_amount'] = $his_fee - $other_list[$i]['personpay_fee'] ."(HIS)";
                        $list['data']['abnormal_data'][$i]['pay_time'] = date("Y-m-d",strtotime($other_list[$i]['pay_time']));
                        $list['data']['abnormal_data'][$i]['patient_id'] = $other_list[$i]['patient_id'];
                        $list['data']['abnormal_data'][$i]['patient_name'] = $other_list[$i]['patient_name'];
                        $list['data']['abnormal_data'][$i]['card_type'] = $card_type;
                        $list['data']['abnormal_data'][$i]['pay_type'] = $pay_type;
                        $list['data']['abnormal_data'][$i]['trade_no'] = $other_list[$i]['trade_no'];
                        $list['data']['abnormal_data'][$i]['other_fee'] = $other_list[$i]['personpay_fee'];
                        $list['data']['abnormal_data'][$i]['business_type'] = $rel_module[0]['name'];
                        $list['data']['abnormal_data'][$i]['operator_id'] = $other_list[$i]['zzj_code']; 
                        //HIS的数据	
                        $list['data']['abnormal_data'][$i]['his_no'] = $his_no;
                        $list['data']['abnormal_data'][$i]['his_fee'] = $his_fee;
                        $list['data']['abnormal_data'][$i]['his_fee_date'] = $his_fee_date;
                       
                    }
                }
            } 


            if($his_have==1){
                //正常  
                $list['data']['data_list'][$i]['patient_id'] = $other_list[$i]['patient_id'];
                $list['data']['data_list'][$i]['patient_name'] = $other_list[$i]['patient_name'];
                $list['data']['data_list'][$i]['card_type'] = $card_type;
                $list['data']['data_list'][$i]['pay_type'] = $pay_type;
                $list['data']['data_list'][$i]['business_type'] = $rel_module[0]['name'];
                $list['data']['data_list'][$i]['operator_id'] = $other_list[$i]['zzj_code']; 
                $list['data']['data_list'][$i]['trade_no'] = $other_list[$i]['trade_no'];
                $list['data']['data_list'][$i]['other_fee'] = $other_list[$i]['personpay_fee'];
                $list['data']['data_list'][$i]['pay_time'] = date("Y-m-d",strtotime($other_list[$i]['pay_time']));
                //HIS的数据	
                $list['data']['data_list'][$i]['his_no'] = $his_no;
                $list['data']['data_list'][$i]['his_fee'] = $his_fee;
                $list['data']['data_list'][$i]['his_fee_date'] = $his_fee_date;
                
            }
            
        } 
        
        dump($list);exit;
       //导出
        if($export=="1"){
            $date = date('Y-m-d H:i:s');
            //dump($date);exit;
            //导入第三方类库
            vendor('PHPExcel.PHPExcel');
            //实例化PHPExcel这个类，相当于在桌面新建了一个Excel的文件
            $PHPExcel = new \PHPExcel();
            //获取当前活动的sheet
            $PHPSheet = $PHPExcel->getActiveSheet();
            //给当前活动的sheet重命名
            $PHPSheet -> setTitle('sheet1');
            //添加表头
            $code = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            $title_array = array('','患者id','患者名字','卡类型','支付方式','业务类型','操作员','自助订单号','自助支付金额','自助支付日期','his订单号','his支付金额','his支付日期');
            
            $excel_filename='帐单'; 
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
         

            foreach($title_array as $key=>$value){
                $PHPSheet->getStyle($code[$key].'2')->getFont()->setBold(true);
                $PHPSheet -> setCellValue($code[$key].'2',$value);
            }
           //dump($list);exit;
            $n=3;

            //添加内容 (his单边账))
            if(array_key_exists("his_abnormal_data", $list['data'])){
                $his_abnormal_data=array_merge($list['data']['his_abnormal_data']);
                
                $m=$n;
                $h=0;
                //echo $n;exit;
                for($i=$n;$i<=count($his_abnormal_data)+$n;$i++){
                    unset($his_abnormal_data[$h]['zzj']);
                    $h++;
                    $j=10;
                    foreach($his_abnormal_data[$i-$m] as $key=>$val){
                        $PHPSheet -> setCellValue($code[$j].$i,$val);
                        $j++;  
                    }
                }  
                
           }

            //添加内容 (自助单边账)
            if(array_key_exists("zzj_abnormal_data", $list['data'])){
                $zzj_abnormal_data=array_merge($list['data']['zzj_abnormal_data']);
                $n=$n+count($his_abnormal_data);
                $m=$n;
                //dump($n);exit;
                $z=0;
                for($i=$m;$i<=count($zzj_abnormal_data)+$m;$i++){
                    //dump($zzj_abnormal_data);exit;
                    unset($zzj_abnormal_data[$z]['his']);
                    $z++;
                    $j=7;
                    foreach($zzj_abnormal_data[$i-$m] as $key=>$val){
                        $PHPSheet -> setCellValue($code[$j].$i,$val);
                        $j++;  
                    }
                }  
                
           }


           //添加内容 (金额不对等)
           if(array_key_exists("abnormal_data", $list['data'])){
                $list_abnormal=array_merge($list['data']['abnormal_data']);
                $n=$n+count($zzj_abnormal_data);
                $m=$n;
                for($i=$m;$i<=count($list_abnormal)+$m;$i++){
                    $j=0;
                    foreach($list_abnormal[$i-$m] as $key=>$val){
                        $PHPSheet -> setCellValue($code[$j].$i,$val);
                        $j++;  
                    }
                }  
           }
           //添加内容 (正常)
           if(array_key_exists("data_list", $list['data'])){
                $list_data=array_merge($list['data']['data_list']);
               // dump($list_data);exit;
                $n=$n+count($list_abnormal);
                $m=$n;
                for($i=$m;$i<=count($list_data)+$m;$i++){
                    $j=1;
                    foreach($list_data[$i-$m] as $key=>$val){
                        $PHPSheet -> setCellValue($code[$j].$i,$val);
                        $j++;  
                    } 
                }
            }
          // dump($list);exit;  
            $h=$n+5;
            $PHPSheet -> setCellValue(B.$h,"自助机笔数:");
            $PHPSheet -> setCellValue(C.$h,$list['data']['total_of']['idnum']);
            $PHPSheet -> setCellValue(E.$h,"自助机金额:"); 
            $PHPSheet -> setCellValue(F.$h,$list['data']['total_of']['tamount']);

            $h2=$h+1;
            $PHPSheet -> setCellValue(B.$h2,"his笔数:");
            $PHPSheet -> setCellValue(C.$h2,$list['data']['total_of']['his_idnum']);
            $PHPSheet -> setCellValue(E.$h2,"his金额:"); 
            $PHPSheet -> setCellValue(F.$h2,$list['data']['total_of']['his_total_amount']);  
            
            $h3=$h2+1;
            $PHPSheet -> setCellValue(B.$h3,"制表时间:"); 
            $PHPSheet -> setCellValue(C.$h3,$date);
           
            
           
 
            $filename=date('YmdHis');
            if($type == '07'){
                //Excel2007 版本   03版本 Excel5
                $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');
                //告诉浏览器要输出的文件
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                //文件的名称
                header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
            }elseif($type == '03'){
                $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel5');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            }  
            //禁止缓存
            header("Cache-Control:max-age=0");
            //输出
            $PHPWriter ->save('php://output');
        }else{
            $this->ajaxReturn($list,'JSON');
        }
        
       
    } 

    //支付宝退款 
    public function alipayRefund(){
        //操作员名称
        $op_name = $_SESSION['uname'];
        //流水号
        $trade_no = I("post.trade_no");
        //商户订单号
        $out_trade_no = I("post.out_trade_no");
        //退款金额
        $amount = I("post.amount");
        $source = 'hos001';
        $soap = new \SoapClient('http://192.168.51.154:8080/soap/Service.php?wsdl');
        $row = $soap->refund($trade_no,$amount,$out_trade_no,$source,$op_name);
        $row = str_replace("gb2312", "utf8", $row);
        $xml = simplexml_load_string($row);
        $xml = (array)$xml;
        $message = $xml['Message'];
        if($message->Code != "10000"){
           $rel['code']='1';
           $rel['msg']='退款失败';
        }else{
           $rel['code']='0';
           $rel['msg']='退款成功';
           
        }
        $this->ajaxReturn($rel,'JSON');

    }


    //微信退款
    public function wxRefund(){
        $op_name = $_SESSION['uname'];
        $trade_no = I("post.trade_no");
        $total_amount = I("post.total_amount");
        $amount = I("post.amount");
        $soap = new \SoapClient('http://192.168.51.154:8080/soap/Service.php?wsdl');
        // $str = $soap->WxRefused("zzj002201706125728","2","1");
        $row = $soap->WxRefused($trade_no,$total_amount,$amount);
        $row = str_replace("gb2312", "utf8", $row);
        $xml = simplexml_load_string($row);
        $xml = (array)$xml;
        $message = $xml['Message'];
        if($message->result_code != "SUCCESS"){
            $rel['code']='1';
            $rel['msg']='退款失败';
        }else{  
            $rel['code']='0';
            $rel['msg']='退款成功';
        }
        $this->ajaxReturn($rel,'JSON');
    }





}