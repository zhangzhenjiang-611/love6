<?php
namespace Manage\Controller;
use Think\Controller;
//引用phpexcel
use PHPExcel;
use PHPExcel_IOFactory;
class DataController extends CommonController {


    /**
     * @desc 根据设备来统计数据
     * @param
     * @author 鲁翠霞
     * @final 2020-04-16
     */
    public function devDataStatistics(){
        //处理并验证入参
        $rules = array(
          array('dev_code', 1),
        );
        $in = $this->validParam($rules);//入参处理     
        $M = M('register');
        $Mf = M('fee');
        $Mc = M();
        //挂号
        $where = "zzj_code='".$in['dev_code']."' and ds_date = '".date('Y-m-d ')."' ";
        $where2 = "zzj_code='".$in['dev_code']."' and ds_date = '".date("Y-m-d",strtotime("-1 week"))."'";
        $where_zzj = "zzj_code='".$in['dev_code']."'";
        //当日
        $rel_list = $M->where($where)->field('count(id) as num ,sum(personpay_fee) as fee')->select();
        //上周同期
        $last_week_rel_list = $M->where($where2)->field('count(id) as last_week_num ,sum(personpay_fee) as last_week_fee')->select();
        //累计
        $total_list =  $M->where($where_zzj)->field('count(id) as total_num ,sum(personpay_fee) as total_fee')->select();
       
        $reg_num_proportion=round($rel_list[0]['num']/$last_week_rel_list[0]['last_week_num']*100,2)."％";
        $reg_fee_proportion=round($rel_list[0]['fee']/$last_week_rel_list[0]['last_week_fee']*100,2)."％";
        
        $data['code'] = '0';
        $data['msg'] = '查询成功';
        $data['data']['reg'] = array_merge($rel_list[0],$total_list[0]);
        $data['data']['reg']['reg_num_proportion'] = $reg_num_proportion;
        $data['data']['reg']['reg_fee_proportion'] = $reg_fee_proportion;


        //缴费
        $start_time = date('Y-m-d'). " 00:00:00";
        $end_time = date('Y-m-d'). " 59:59:59";
        $s_week = date("Y-m-d",strtotime("-1 week")) . " 00:00:00";
        $e_week = date("Y-m-d",strtotime("-1 week")) . " 59:59:59";
        $where3 = "zzj_code='".$in['dev_code']."' and create_time>='".$start_time."' and create_time<='".$end_time."' ";
        $where4 = "zzj_code='".$in['dev_code']."' and create_time>='".$s_week."' and create_time<='".$e_week."'";
        //当日
        $fee_list = $Mf->where($where3)->field('count(id) as num ,sum(personpay_fee) as fee')->select();
        //上周同期
        $last_week_fee_list = $Mf->where($where4)->field('count(id) as last_week_num ,sum(personpay_fee) as last_week_fee')->select();
        //累计
        $total_fee_list =  $Mf->where($where_zzj)->field('count(id) as total_num ,sum(personpay_fee) as total_fee')->select();

        //运算
        $fee_num_proportion=round($fee_list[0]['num']/$last_week_fee_list[0]['last_week_num']*100,2)."％";
        $fee_fee_proportion=round($fee_list[0]['fee']/$last_week_fee_list[0]['last_week_fee']*100,2)."％";

        $data['data']['fee'] = array_merge($fee_list[0],$total_fee_list[0]);
        $data['data']['fee']['fee_num_proportion'] = $fee_num_proportion;
        $data['data']['fee']['fee_fee_proportion'] = $fee_fee_proportion;


        //建卡
        $pre = C('DB_PREFIX');//表前缀
        //当日
        $sql = "select  sum(t2.personpay_fee) as fee ,count(t1.id) as num  from `{$pre}create` as t1 left join `{$pre}payment` as t2 on t1.payment_id=t2.id where  t1.zzj_code='".$in['dev_code']."' and t1.create_time>='".$start_time."' and t1.create_time<='".$end_time."'";
        //上周同期
        $sql2 = "select sum(t2.personpay_fee) as last_week_fee ,count(t1.id) as last_week_num   from `{$pre}create` as t1 left join `{$pre}payment` as t2 on t1.payment_id=t2.id where  t1.zzj_code='".$in['dev_code']."' and t1.create_time>='".$s_week."' and t1.create_time<='".$e_week."'";
        //累计 
        $sql3 = "select sum(t2.personpay_fee) as total_fee ,count(t1.id) as total_num   from `{$pre}create` as t1 left join `{$pre}payment` as t2 on t1.payment_id=t2.id where  t1.zzj_code='".$in['dev_code']."' ";
        $sql_return = $Mc->query($sql);
        $sql_return2 = $Mc->query($sql2);
        $sql_return3 = $Mc->query($sql3);
   
        $create_num_proportion=round($sql_return[0]['num']/$sql_return2[0]['last_week_num']*100,2)."％";
        $create_fee_proportion=round($sql_return[0]['fee']/$sql_return2[0]['last_week_fee']*100,2)."％";

        $data['data']['create'] = array_merge($sql_return[0],$sql_return3[0]);
        $data['data']['create']['create_num_proportion'] = $create_num_proportion;
        $data['data']['create']['create_fee_proportion'] = $create_fee_proportion;
    
        $this->ajaxReturn($data,'JSON');
       
    }
 


    /**
     * @desc 费用查询
     * @param 
     * @author 鲁翠霞
     * @final 2020-04-15
     */
    public function feeQuery(){
      //处理并验证入参
      $rules = array(
        array('patient_id', 0),//患者id
        array('id_no', 0),//身份证
        array('trade_no', 0),//订单号
        array('start_time', 0, '/^\d{4}-\d{2}-\d{2}$/'),
        array('end_time', 0, '/^\d{4}-\d{2}-\d{2}$/'),
        array('pay_type', 0),//支付方式:1支付宝2微信3银行卡4京医通(卡余额支付) 
        array('per_page',0,'/^\d+$/'),//第几页
        array('export',0)//为1时，导出excel
      );
      $in = $this->validParam($rules);//入参处理
      //$in['export']=1;
      $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
      $in['start_time'] = (!empty($in['start_time']) ? $in['start_time'] .' 00:00:00': $in['start_time']);
      $in['end_time'] = (!empty($in['end_time']) ? $in['end_time'] : date('Y-m-d')).' 23:59:59';
      //dump($in);
      //过滤条件
      $where = '';
      if(!empty($in['id_no'])){
        $where .=" and t3.id_no='".$in['id_no']."'";
      }
      if(!empty($in['trade_no'])){
        $where .=" and t2.trade_no='".$in['trade_no']."'";
      }
      if(!empty($in['patient_id'])){
        $where .=" and t1.patient_id='".$in['patient_id']."'";
      }
      if(!empty($in['pay_type'])){
        $where .=" and t2.pay_type='".$in['pay_type']."'";
      }
      if(!empty($in['start_time'])){
        $where .=" and t1.create_time>='".$in['start_time']."'";
      }
      if(!empty($in['end_time'])){
        $where .=" and t1.create_time<='".$in['end_time']."'";
      }
      $where = 'where '.ltrim($where," and");
 
      //组织sql
      $pre = C('DB_PREFIX');//表前缀

      $sql_select_field = "select t1.id,t1.payment_id, t1.patient_id,t1.card_type,t1.card_no,t1.personpay_fee ,t3.id_no,t3.mobile,t3.name,t3.gender,t2.trade_no,t2.personpay_fee, t2.pay_type,t2.pay_time from ";

      $sql_join_where = "(`{$pre}fee` as t1 left join `{$pre}payment` as t2 on  t1.payment_id=t2.id) left join `{$pre}patient` as t3 on t1.patient_id=t3.patient_id $where";

      $M = M();
      //每页显示多少条
      $page_num = $M->query("select conf_value from {$pre}config where conf_key='per.page.num'");
      $page_num = $page_num[0]['conf_value'];

      //查询总记录数
      $count = $M->query("select count(*) as tp_cnt from $sql_join_where");
      $count = intval($count[0]['tp_cnt']);

      //共多少页
      $pages = ceil($count/$page_num);

      //开始分页
      $in['per_page'] = $in['per_page'] - 1;
      $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];

      $offset = $in['per_page'] * $page_num;//偏移量
      $sql_order_limit = ' order by t1.create_time desc limit '.$offset.','.$page_num;

      $result = array();
      $result['code'] = '0';
      $result['msg'] = '查询成功';

      $result['data']['page']['total_row_num'] = $count;//总条数
      $result['data']['page']['total_page_num'] = $pages;//总页数
      $result['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
      $result['data']['page']['page_row_num'] = $page_num;//每页多少条

      $result['data']['list'] = $M->query($sql_select_field.$sql_join_where.$sql_order_limit);
      //dump($result['data']['list']);exit;
      $title_array = array('患者id','卡号','卡类型','个人支付金额','身份证号','手机号','姓名','性别','订单号','支付方式','支付时间'); 
      $excel_list = $result['data']['list'];
      foreach($excel_list as $k=>$v){ 
         //性别0未知1男2女
         if($v['gender']=='1'){
           $excel_list[$k]['gender'] = '男';
         }elseif($v['gender']=='2'){
           $excel_list[$k]['gender'] = '女';
         }elseif($v['gender']=='0'){
           $excel_list[$k]['gender'] = '未知';
         }
         //患者卡类型:1社保卡2京医通卡3身份证4就诊卡' 
         if($v['card_type']=='1'){
           $excel_list[$k]['card_type'] = '社保卡';
         }elseif($v['card_type']=='2'){
           $excel_list[$k]['card_type'] = '京医通卡';
         }elseif($v['card_type']=='3'){
           $excel_list[$k]['card_type'] = '身份证';
         }elseif($v['card_type']=='4'){
          $excel_list[$k]['card_type'] = '就诊卡';
        }
         //支付方式:1支付宝2微信3银行卡4京医通(卡余额支付)
         if($v['pay_type']=='1'){
          $excel_list[$k]['pay_type'] = '支付宝';
        }elseif($v['pay_type']=='2'){
          $excel_list[$k]['pay_type'] = '微信';
        }elseif($v['pay_type']=='3'){
          $excel_list[$k]['pay_type'] = '银行卡';
        }elseif($v['pay_type']=='4'){
          $excel_list[$k]['pay_type'] = '京医通(卡余额支付)';
        }
         unset($excel_list[$k]['payment_id']);
         unset($excel_list[$k]['id']);
      }
      //$export="1";
      if($in['export']=="1"){
           $excel_filename='缴费帐单';  
           $this->export($excel_list,$title_array,$excel_filename );
      }else{
            $this->ajaxReturn($result,'JSON');
      }

    }

    /**
     * @desc 挂号查询
     * @param
     * @author 鲁翠霞
     * @fianal 2020-04-20
     */
    public function registerQuery(){
      $rules = array(
        array('id_no',0),//身份证
        array('patient_id',0),//患者id
        array('card_type',0),//患者卡类型:1社保卡2京医通卡3身份证
        array('start_time',0), 
        array('end_time',0),
        array('card_no',0),//卡号
        array('reg_type',0,'/^([1-2])$/'),//挂号类型  1：挂号 2：预约挂号 
        array('dept_name',0),//科室
        array('per_page',0,'/^\d+$/'),//第几页
        array('export',0)//为1时，导出excel
      );
      $in = $this->validParam($rules);//入参处理
      //dump($in);exit;
      $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
      $start_time = (!empty($in['start_time'])) ? $in['start_time'] : $in['start_time'];
      $end_time = (!empty($in['end_time'])) ? $in['end_time'] : '';
      //  $in['export'] =1;
      $where = '';
      if(!empty($in['id_no'])){
        $where.=" and t2.id_no='".$in['id_no']."'";
      }
      if(!empty($in['dept_name'])){
        $where.=" and t1.dept_name='".$in['dept_name']."'";
      }
      if(!empty($in['card_no'])){
        $where.=" and t1.card_no='".$in['card_no']."'";
      }
      if(!empty($in['card_type'])){
        $where.=" and t1.card_type='".$in['card_type']."'";
      }
      if(!empty($in['patient_id'])){
        $where.=" and t1.patient_id='".$in['patient_id']."'";
      }
      if(!empty($start_time)){
        $where.=" and t1.ds_date>='".$start_time."' ";
      }
      if(!empty($end_time)){
        $where.=" and t1.ds_date<='".$end_time."'";
      }
      $where = ltrim($where," and");
     
      
      $field="t1.id,t1.dept_name,t1.patient_id,t1.card_no,t1.card_type,t1.personpay_fee,t2.name,t2.mobile as phone,t2.id_no,t2.birthday,t2.gender,t1.ds_date";
      $M = M();
      $pre = C('DB_PREFIX');//表前缀
     
      //每页显示多少条
      $page_num = $M->query("select conf_value from {$pre}config where conf_key='per.page.num'");
      $page_num = $page_num[0]['conf_value'];
      //dump($page_num);exit;
      //查询总记录数
      if(empty($where)){
          $sql1 = "select count(*) as tp_cnt from `{$pre}register` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id";
          $sql2 = "select count(*) as tp_cnt from `{$pre}appoint` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id";
      }else{
          $sql1 = "select count(*) as tp_cnt from `{$pre}register` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id where {$where}";
          $sql2 = "select count(*) as tp_cnt from `{$pre}appoint` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id where {$where}";
      }
       $count1 = $M->query($sql1);
       $count2 = $M->query($sql2);
       $count = intval($count1[0]['tp_cnt']+$count2[0]['tp_cnt']);
   
      //共多少页
      $pages = ceil($count/$page_num);
      //开始分页
      $in['per_page'] = $in['per_page'] - 1;
      $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];
      $offset = $in['per_page'] * $page_num;//偏移量
      if(empty($where)){
          if($in['reg_type']==1){//1：挂号
              $sql = "select {$field} from `{$pre}register` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id  order  by t1.create_time desc limit {$offset},{$page_num}"; 
              $rel = $M->query($sql);
              foreach($rel as $k=>$v){
                $rel[$k]['reg_type']='当日挂号'; 
              }
          }elseif($in['reg_type']==2){
              $sql = "select {$field} from `{$pre}appoint` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id   order  by t1.create_time desc limit {$offset},{$page_num}";
              $rel = $M->query($sql);
              foreach($rel as $k=>$v){
                  $rel[$k]['reg_type']='预约挂号'; 
              }
          }elseif($in['reg_type']==''){
              $sql1 = "select {$field} from `{$pre}register` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id  order  by t1.create_time desc limit {$offset},{$page_num}"; 
              $rel1 = $M->query($sql1);
              foreach($rel1 as $k=>$v){ 
                  $rel1[$k]['reg_type']='当日挂号'; 
              }
              $sql2 = "select {$field} from `{$pre}appoint` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id   order  by t1.create_time desc limit {$offset},{$page_num}";
              $rel2 = $M->query($sql2);
              foreach($rel2 as $k=>$v){
                $rel2[$k]['reg_type']='预约挂号'; 
              }            
              $rel = array_merge($rel1,$rel2);
          }
      }else{
          if($in['reg_type']==1){//1：挂号
              $sql = "select {$field} from `{$pre}register` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id where ".$where."  order  by t1.create_time desc limit {$offset},{$page_num}"; 
              $rel = $M->query($sql);
              foreach($rel as $k=>$v){
                $rel[$k]['reg_type']='今日挂号'; 
              }
          }elseif($in['reg_type']==2){
              $sql = "select {$field} from `{$pre}appoint` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id where ".$where."  order  by t1.create_time desc limit {$offset},{$page_num}";
              $rel = $M->query($sql);
              foreach($rel as $k=>$v){
                  $rel[$k]['reg_type']='预约挂号'; 
              }
          }elseif($in['reg_type']==''){
              $sql1 = "select {$field} from `{$pre}register` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id where ".$where."  order  by t1.create_time desc limit {$offset},{$page_num}"; 
              $rel1 = $M->query($sql1);
              foreach($rel1 as $k=>$v){
                $rel1[$k]['reg_type']='今日挂号'; 
              }
              $sql2 = "select {$field} from `{$pre}appoint` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id where ".$where."  order  by t1.create_time desc limit {$offset},{$page_num}";
              $rel2 = $M->query($sql2);
              foreach($rel2 as $k=>$v){
                  $rel2[$k]['reg_type']='预约挂号'; 
              }
              $rel = array_merge($rel1,$rel2);

          }
      }
      $rel_ist=array(); 
      $rel_list['code']='0';
      $rel_list['msg']='查询成功';
      $rel_list['data']['page']['total_row_num'] = $count;//总条数
      $rel_list['data']['page']['total_page_num'] = $pages;//总页数
      $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
      $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条
      $rel_list['data']['list']=$rel;
      //dump($rel);exit;
      $title_array = array('科室名','患者id','卡号','卡类型','个人支付金额','姓名','手机号','身份证号','出生日期','性别','挂号日期','挂号类别'); 
      $excel_list = $rel;
      foreach($excel_list as $k=>$v){ 
         //性别0未知1男2女
         if($v['gender']=='1'){
           $excel_list[$k]['gender'] = '男';
         }elseif($v['gender']=='2'){
           $excel_list[$k]['gender'] = '女';
         }elseif($v['gender']=='0'){
           $excel_list[$k]['gender'] = '未知';
         }
         //患者卡类型:1社保卡2京医通卡3身份证4就诊卡'
         if($v['card_type']=='1'){
           $excel_list[$k]['card_type'] = '社保卡';
         }elseif($v['card_type']=='2'){
           $excel_list[$k]['card_type'] = '京医通卡';
         }elseif($v['card_type']=='3'){
           $excel_list[$k]['card_type'] = '身份证';
         }elseif($v['card_type']=='4'){
          $excel_list[$k]['card_type'] = '就诊卡';
        }
       
         unset($excel_list[$k]['id']);
      }
   
      //$export="1";
      if($in['export']=="1"){
           $excel_filename='挂号帐单'; 
           $this->export($excel_list,$title_array,$excel_filename );
      }else{
        $this->ajaxReturn($rel_list,'JSON');
      }

  }

    public function registerQuery11(){
        $rules = array(
          array('id_no',0),//身份证
          array('patient_id',0),//患者id
          array('card_type',0),//患者卡类型:1社保卡2京医通卡3身份证
          array('start_time',0), 
          array('end_time',0),
          array('card_no',0),//卡号
          array('reg_type',0,'/^([1-2])$/'),//挂号类型  1：挂号 2：预约挂号 
          array('dept_name',0),//科室
          array('per_page',0,'/^\d+$/'),//第几页
          array('export',0)//为1时，导出excel
        );
        $in = $this->validParam($rules);//入参处理
        //dump($in);exit;
        $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
        $start_time = (!empty($in['start_time'])) ? $in['start_time'] : $in['start_time'];
        $end_time = (!empty($in['end_time'])) ? $in['end_time'] : '';
        //  $in['export'] =1;
        $where = '';
        if(!empty($in['id_no'])){
          $where.=" and t2.id_no='".$in['id_no']."'";
        }
        if(!empty($in['dept_name'])){
          $where.=" and t1.dept_name='".$in['dept_name']."'";
        }
        if(!empty($in['card_no'])){
          $where.=" and t1.card_no='".$in['card_no']."'";
        }
        if(!empty($in['card_type'])){
          $where.=" and t1.card_type='".$in['card_type']."'";
        }
        if(!empty($in['patient_id'])){
          $where.=" and t1.patient_id='".$in['patient_id']."'";
        }
        if(!empty($start_time)){
          $where.=" and t1.ds_date>='".$start_time."' ";
        }
        if(!empty($end_time)){
          $where.=" and t1.ds_date<='".$end_time."'";
        }
        $where = ltrim($where," and");
       
        
        $field="t1.id,t1.dept_name,t1.patient_id,t1.card_no,t1.card_type,t1.personpay_fee,t2.name,t2.mobile as phone,t2.id_no,t2.birthday,t2.gender,t1.ds_date";
        $M = M();
        $pre = C('DB_PREFIX');//表前缀
       
        //每页显示多少条
        $page_num = $M->query("select conf_value from {$pre}config where conf_key='per.page.num'");
        $page_num = $page_num[0]['conf_value'];
        //dump($page_num);exit;
        //查询总记录数
        if(empty($where)){
            $sql1 = "select count(*) as tp_cnt from `{$pre}register` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id";
            $sql2 = "select count(*) as tp_cnt from `{$pre}appoint` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id";
        }else{
            $sql1 = "select count(*) as tp_cnt from `{$pre}register` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id where {$where}";
            $sql2 = "select count(*) as tp_cnt from `{$pre}appoint` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id where {$where}";
        }
         $count1 = $M->query($sql1);
         $count2 = $M->query($sql2);
         $count = intval($count1[0]['tp_cnt']+$count2[0]['tp_cnt']);
     
        //共多少页
        $pages = ceil($count/$page_num);
        //开始分页
        $in['per_page'] = $in['per_page'] - 1;
        $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];
        $offset = $in['per_page'] * $page_num;//偏移量
        if(empty($where)){
            if($in['reg_type']==1){//1：挂号
                $sql = "select {$field} from `{$pre}register` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id  order  by t1.create_time desc limit {$offset},{$page_num}"; 
                $rel = $M->query($sql);
                foreach($rel as $k=>$v){
                  $rel[$k]['reg_type']='当日挂号'; 
                }
            }elseif($in['reg_type']==2){
                $sql = "select {$field} from `{$pre}appoint` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id   order  by t1.create_time desc limit {$offset},{$page_num}";
                $rel = $M->query($sql);
                foreach($rel as $k=>$v){
                    $rel[$k]['reg_type']='预约挂号'; 
                }
            }elseif($in['reg_type']==''){
                $sql1 = "select {$field} from `{$pre}register` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id  order  by t1.create_time desc limit {$offset},{$page_num}"; 
                $rel1 = $M->query($sql1);
                foreach($rel1 as $k=>$v){ 
                    $rel1[$k]['reg_type']='当日挂号'; 
                }
                $sql2 = "select {$field} from `{$pre}appoint` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id   order  by t1.create_time desc limit {$offset},{$page_num}";
                $rel2 = $M->query($sql2);
                foreach($rel2 as $k=>$v){
                  $rel2[$k]['reg_type']='预约挂号'; 
                }            
                $rel = array_merge($rel1,$rel2);
            }
        }else{
            if($in['reg_type']==1){//1：挂号
                $sql = "select {$field} from `{$pre}register` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id where ".$where."  order  by t1.create_time desc limit {$offset},{$page_num}"; 
                $rel = $M->query($sql);
                foreach($rel as $k=>$v){
                  $rel[$k]['reg_type']='今日挂号'; 
                }
            }elseif($in['reg_type']==2){
                $sql = "select {$field} from `{$pre}appoint` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id where ".$where."  order  by t1.create_time desc limit {$offset},{$page_num}";
                $rel = $M->query($sql);
                foreach($rel as $k=>$v){
                    $rel[$k]['reg_type']='预约挂号'; 
                }
            }elseif($in['reg_type']==''){
                $sql1 = "select {$field} from `{$pre}register` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id where ".$where."  order  by t1.create_time desc limit {$offset},{$page_num}"; 
                $rel1 = $M->query($sql1);
                foreach($rel1 as $k=>$v){
                  $rel1[$k]['reg_type']='今日挂号'; 
                }
                $sql2 = "select {$field} from `{$pre}appoint` as t1 left join `{$pre}patient` as t2 on  t1.patient_id=t2.patient_id where ".$where."  order  by t1.create_time desc limit {$offset},{$page_num}";
                $rel2 = $M->query($sql2);
                foreach($rel2 as $k=>$v){
                    $rel2[$k]['reg_type']='预约挂号'; 
                }
                $rel = array_merge($rel1,$rel2);

            }
        }
        $rel_ist=array(); 
        $rel_list['code']='0';
        $rel_list['msg']='查询成功';
        $rel_list['data']['page']['total_row_num'] = $count;//总条数
        $rel_list['data']['page']['total_page_num'] = $pages;//总页数
        $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
        $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条
        $rel_list['data']['list']=$rel;
        //dump($rel);exit;
        $title_array = array('科室名','患者id','卡号','卡类型','个人支付金额','姓名','手机号','身份证号','出生日期','性别','挂号日期','挂号类别'); 
        $excel_list = $rel;
        foreach($excel_list as $k=>$v){ 
           //性别0未知1男2女
           if($v['gender']=='1'){
             $excel_list[$k]['gender'] = '男';
           }elseif($v['gender']=='2'){
             $excel_list[$k]['gender'] = '女';
           }elseif($v['gender']=='0'){
             $excel_list[$k]['gender'] = '未知';
           }
           //患者卡类型:1社保卡2京医通卡3身份证4就诊卡'
           if($v['card_type']=='1'){
             $excel_list[$k]['card_type'] = '社保卡';
           }elseif($v['card_type']=='2'){
             $excel_list[$k]['card_type'] = '京医通卡';
           }elseif($v['card_type']=='3'){
             $excel_list[$k]['card_type'] = '身份证';
           }elseif($v['card_type']=='4'){
            $excel_list[$k]['card_type'] = '就诊卡';
          }
         
           unset($excel_list[$k]['id']);
        }
     
        //$export="1";
        if($in['export']=="1"){
             $excel_filename='挂号帐单'; 
             $this->export($excel_list,$title_array,$excel_filename );
        }else{
          $this->ajaxReturn($rel_list,'JSON');
        }

    }

    /**
     * @desc 建档查询
     * @param
     * @author 鲁翠霞
     * @fianal 2020-04-20
     */
    public function newPatQuery(){
      
       //1是必传参数
       $rules = array(
        array('patient_id',0),//患者id
        array('phone',0),//手机号
        array('id_no',0),//身份证号
        array('name',0),//姓名 
        array('new_card_no',0),//卡号
        array('start_time',0,'/^\d{4}-\d{2}-\d{2}$/'),
        array('end_time',0,'/^\d{4}-\d{2}-\d{2}$/'),
        array('pay_type',0),//支付方式:1支付宝2微信3银行卡4京医通(卡余额支付)
        array('per_page',0,'/^\d+$/'),//第几页
        array('export',0)//为1时，导出excel
      );
       $in = $this->validParam($rules);//入参处理
       $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
       $start_time = (!empty($in['start_time'])) ? $in['start_time'].' 00:00:00' : $in['start_time'];
       $end_time = (!empty($in['end_time'])) ? $in['end_time'].' 23:59:59' :  date('Y-m-d').' 23:59:59';
       //建档状态:1成功0失败
       $where = " t1.state='1'";
       if(!empty($in['patient_id'])){
         $where.=" and t1.patient_id='".$in['patient_id']."'";
       }
       if(!empty($in['phone'])){
         $where.=" and t1.phone='".$in['phone']."'";
       }
       if(!empty($in['id_no'])){
        $where.=" and t1.id_no='".$in['id_no']."'";
      }
      if(!empty($in['name'])){
        $where.=" and t1.name='".$in['name']."'";
      }
      if(!empty($in['pay_type'])){
        $where.=" and t2.pay_type='".$in['pay_type']."'";
      }
      if(!empty($in['new_card_no'])){
        $where.=" and t1.new_card_no='".$in['new_card_no']."'";
      }
      if(!empty($start_time)){
         $where.=" and t1.create_time>='".$start_time."' ";
      }
      if(!empty($end_time)){
        $where.="  and t1.create_time<='".$end_time."'";
      }
       $where.=" and t2.pay_status='1'";
       $M = M();
       $pre = C('DB_PREFIX');//表前缀

       //每页显示多少条
       $page_num = $M->query("select conf_value from {$pre}config where conf_key='per.page.num'");
       $page_num = $page_num[0]['conf_value'];
    
      // 查询总记录数
       $count = $M->query("select count(*) as tp_cnt from `{$pre}create` as t1 left join `{$pre}payment` as t2 on t1.payment_id=t2.id where {$where}");
       $count = intval($count[0]['tp_cnt']);

       //共多少页
       $pages = ceil($count/$page_num);
       //开始分页
       $in['per_page'] = $in['per_page'] - 1;
       $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];
       $offset = $in['per_page'] * $page_num;//偏移量


       $field = "t1.id,t1.patient_id,t1.name,t1.gender,t1.phone,t1.id_no,t1.new_card_no,t1.type,t1.payment_id,t2.personpay_fee,t1.create_time as pay_time,t2.pay_type";
       $sql = "select {$field} from `{$pre}create` as t1 left join `{$pre}payment` as t2 on t1.payment_id=t2.id where {$where} order by pay_time desc limit {$offset},{$page_num}";
       $rel = $M->query($sql);

        if(count($rel)>0){
         $rel_list['code']='0';
         $rel_list['msg']='查询成功'; 
       }else{
         $rel_list['code']='1'; 
         $rel_list['msg']='无数据';
       }
       $rel_list['code']='0';
       $rel_list['msg']='查询成功';
       $rel_list['data']['page']['total_row_num'] = $count;//总条数
       $rel_list['data']['page']['total_page_num'] = $pages;//总页数
       $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
       $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条
       $rel_list['data']['list']=$rel;

       $title_array = array('患者id','名字','性别','手机号','身份证号','卡号','患者类型','个人支付金额','支付时间','支付方式'); 
       $excel_list = $rel;
       foreach($excel_list as $k=>$v){
          //性别0未知1男2女
          if($v['gender']=='1'){
            $excel_list[$k]['gender'] = '男';
          }elseif($v['gender']=='2'){
            $excel_list[$k]['gender'] = '女';
          }elseif($v['gender']=='0'){
            $excel_list[$k]['gender'] = '未知';
          }
          //患者类型:1医疗保险2自费3新农合
          if($v['type']=='1'){
            $excel_list[$k]['type'] = '医疗保险';
          }elseif($v['type']=='2'){
            $excel_list[$k]['type'] = '自费';
          }elseif($v['type']=='3'){
            $excel_list[$k]['type'] = '新农合';
          }
          //支付方式:1支付宝2微信3银行卡4京医通(卡余额支付)
          if($v['pay_type']=='1'){
            $excel_list[$k]['pay_type'] = '支付宝';
          }elseif($v['pay_type']=='2'){
            $excel_list[$k]['pay_type'] = '微信';
          }elseif($v['pay_type']=='3'){
            $excel_list[$k]['pay_type'] = '银行卡';
          }elseif($v['pay_type']=='4'){
            $excel_list[$k]['pay_type'] = '京医通(卡余额支付)';
          }
          unset($excel_list[$k]['payment_id']);
          unset($excel_list[$k]['id']);
       }

       if($in['export']=="1"){
            $excel_filename='建档查询帐单'; 
            $this->export($excel_list,$title_array,$excel_filename );
       }else{
            $this->ajaxReturn($rel_list,'JSON');
       }
  
    } 

    /**
     * @desc 建档统计
     * @param
     * @author 鲁翠霞
     * @fianal 2020-04-21 
     */
    public function newPatStatistics(){
       //1是必传参数
      $rules = array(
        array('date',0,'/^d|m|y$/'),//d,m,y  天，月，年 
        array('start_time',0,'/^\d{4}-\d{2}-\d{2}$/'),
        array('end_time',0,'/^\d{4}-\d{2}-\d{2}$/'),
        array('per_page',0,'/^\d+$/'),//第几页
        array('export',0)//为1时，导出excel
      );
       $in = $this->validParam($rules);//入参处理
       $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
       $start_time = (!empty($in['start_time'])) ? $in['start_time'].' 00:00:00' : $in['start_time'];
       $end_time = (!empty($in['end_time'])) ? $in['end_time'].' 23:59:59' :  date('Y-m-d').' 23:59:59';
       //建档状态:1成功0失败
       $where = " t1.state='1'";
      if(!empty($start_time)){
        $where.=" and t1.create_time>='".$start_time."' ";
      }
      if(!empty($end_time)){
        $where.="  and t1.create_time<='".$end_time."'";
      }
   
       $M = M();
       $pre = C('DB_PREFIX');//表前缀

       //每页显示多少条
       $page_num = $M->query("select conf_value from {$pre}config where conf_key='per.page.num'");
       $page_num = $page_num[0]['conf_value'];
    
      
       //开始分页
       $in['per_page'] = $in['per_page'] - 1;
       $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];
       $offset = $in['per_page'] * $page_num;//偏移量

       if($in['date']=='d'){
          $field_date = "left(t1.create_time,10) as date";
       }elseif($in['date']=='m'){
          $field_date = "left(t1.create_time,7) as date";
       }elseif($in['date']=='y'){
          $field_date = "left(t1.create_time,4) as date";
       }elseif($in['date']==''){
         $field_date = "left(t1.create_time,10) as date";
       }
       $field = "count(t1.id) as num,sum(t2.personpay_fee) as fee ,sum(if(t1.is_special='1',1,0)) as nonlocal_num,{$field_date}";
       $sql = "select {$field} from `{$pre}create` as t1 left join `{$pre}payment` as t2 on t1.payment_id=t2.id where {$where} group by date order by date desc limit {$offset},{$page_num}";
       $rel = $M->query($sql);

       // 查询总记录数
       $count = $M->query("select {$field} from `{$pre}create` as t1 left join `{$pre}payment` as t2 on t1.payment_id=t2.id where {$where} group by date");
       $count = intval(count($count));

       //共多少页
       $pages = ceil($count/$page_num);

        if(count($rel)>0){
         $rel_list['code']='0';
         $rel_list['msg']='查询成功'; 
       }else{
         $rel_list['code']='1'; 
         $rel_list['msg']='无数据';
       }
       foreach($rel as $k=>$v){
         $rel[$k]['local_num'] = $v['num'] - $v['nonlocal_num'];
       }
       $rel_list['code']='0';
       $rel_list['msg']='查询成功';
       $rel_list['data']['page']['total_row_num'] = $count;//总条数
       $rel_list['data']['page']['total_page_num'] = $pages;//总页数
       $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
       $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条
       $rel_list['data']['list']=$rel;
      
       $title_array = array('建卡数量','建卡金额','外地建卡','日期','本地建卡'); 
       $excel_list = $rel;
       //$export="1";
       if($in['export']=="1"){
            $excel_filename='建档统计'; 
            $this->export($excel_list,$title_array,$excel_filename );
       }else{
            $this->ajaxReturn($rel_list,'JSON');
       }
       
    }

    /**
     * @desc 费用统计
     * @param 
     * @author 鲁翠霞
     * @final 2020-04-21
     */ 
    public function feeStatistics(){
      //处理并验证入参
      $rules = array(
        array('date', 0,'/^d|m|y$/'),//d,m,y  天，月，年 
        array('start_time', 0, '/^\d{4}-\d{2}-\d{2}$/'),
        array('end_time', 0, '/^\d{4}-\d{2}-\d{2}$/'),
        array('per_page',0,'/^\d+$/'),//第几页
        array('export',0)//为1时，导出excel
      );
      $in = $this->validParam($rules);//入参处理

      $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
      $in['start_time'] = (!empty($in['start_time']) ? $in['start_time'] .' 00:00:00': $in['start_time']);
      $in['end_time'] = (!empty($in['end_time']) ? $in['end_time'] : date('Y-m-d')).' 23:59:59';
      //dump($in);
      //过滤条件
      $where = '';
      if(!empty($in['start_time'])){
        $where .=" and t1.create_time>='".$in['start_time']."'";
      }
      if(!empty($in['end_time'])){
        $where .=" and t1.create_time<='".$in['end_time']."'";
      }
      $where = 'where '.ltrim($where," and");
 
      //组织sql
      $pre = C('DB_PREFIX');//表前缀
      if($in['date']=='d'){
        $field_date = "left(t1.create_time,10) as date";
      }elseif($in['date']=='m'){
        $field_date = "left(t1.create_time,7) as date";
      }elseif($in['date']=='y'){
        $field_date = "left(t1.create_time,4) as date";
      }elseif($in['date']==''){
       $field_date = "left(t1.create_time,10) as date";
      }
      
      $sql_select_field = "select count(t1.id) as num,sum(t1.personpay_fee) as fee ,
                            count(if(t1.card_type='1',true,null)) as yb_num , 
                            count(if(t1.card_type!='1',true,null)) as hos_num ,
                            count(if(t2.pay_type='4',true,null)) as jz_num, 
                            sum(if(t2.pay_type='4',true,null)) as jz_fee, 
                            count(if(t2.pay_type='2',true,null)) as wechat_num, 
                            sum(if(t2.pay_type='2',true,null)) as wechat_fee,
                            count(if(t2.pay_type='1',true,null)) as alipay_num, 
                            sum(if(t2.pay_type='1',true,null)) as aliapy_fee,
                            count(if(t2.pay_type='3',true,null)) as bank_num, 
                            sum(if(t2.pay_type='3',true,null)) as bank_fee ,{$field_date} from ";

      $sql_join_where = "(`{$pre}fee` as t1 left join `{$pre}payment` as t2 on  t1.payment_id=t2.id)  $where";
     

      $M = M();
      //每页显示多少条
      $page_num = $M->query("select conf_value from {$pre}config where conf_key='per.page.num'");
      $page_num = $page_num[0]['conf_value'];

      //查询总记录数
      $count = $M->query($sql_select_field.$sql_join_where."group by date");
      $count = intval(count($count));

      //共多少页
      $pages = ceil($count/$page_num);

      //开始分页
      $in['per_page'] = $in['per_page'] - 1;
      $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];

      $offset = $in['per_page'] * $page_num;//偏移量
      $sql_order_limit = ' group by date order by date desc limit '.$offset.','.$page_num;

      $result = array();
      $result['code'] = '0';
      $result['msg'] = '查询成功';

      $result['data']['page']['total_row_num'] = $count;//总条数
      $result['data']['page']['total_page_num'] = $pages;//总页数
      $result['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
      $result['data']['page']['page_row_num'] = $page_num;//每页多少条

      $result['data']['list'] = $M->query($sql_select_field.$sql_join_where.$sql_order_limit);
  
      $title_array = array('缴费笔数','缴费金额','医保数量','自费数量','就诊卡数量','就诊卡金额','微信数量','微信金额','支付宝数量','支付宝金额','银行卡数量','银行卡金额','时间');  
      $excel_list = $result['data']['list'];
      //$export="1";
      if($in['export']=="1"){
           $excel_filename='缴费统计';  
           $this->export($excel_list,$title_array,$excel_filename );
      }else{
          $this->ajaxReturn($result,'JSON');
      }
    }

    /**
     * @desc 挂号统计
     * @param
     * @author 鲁翠霞
     * @fianal 2020-04-21
     */
    public function registerStatistics(){
      $rules = array(
        array('date',0, '/^d|m|y$/'),//d,m,y  天，月，年 
        array('start_time', 0, '/^\d{4}-\d{2}-\d{2}$/'),
        array('end_time', 0, '/^\d{4}-\d{2}-\d{2}$/'),
        array('per_page',0,'/^\d+$/'),//第几页
        array('export',0)//为1时，导出excel
      );
      $in = $this->validParam($rules);//入参处理
      //dump($_POST);exit;
      $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
      $start_time = (!empty($in['start_time'])) ? $in['start_time'] : $in['start_time'];
      $end_time = (!empty($in['end_time'])) ? $in['end_time'] : '';
  
       $where = '';
      if(!empty($start_time)){
        $where.=" and t.ds_date>='".$start_time."' ";
      }
      if(!empty($end_time)){
        $where.=" and t.ds_date<='".$end_time."'";
      }
      $where = ltrim($where," and");
      if($in['date']=='d'){
        $field_date = "ds_date as date";
      }elseif($in['date']=='m'){
        $field_date = "left(ds_date,7) as date";
      }elseif($in['date']=='y'){
        $field_date = "left(ds_date,4) as date";
      }elseif($in['date']==''){
        $field_date = "ds_date as date";
      }
      $field="sum(t.personpay_fee)as fee,  COUNT(t.personpay_fee) AS num ,
      count(if(t='r',true,null)) as reg_num,count(if(t='a',true,null))as app_num,
      count(if(card_type='1',true,null)) as yb_num,count(if(card_type!='1',true,null)) as hos_num ,{$field_date}";
      $M = M();
      $pre = C('DB_PREFIX');//表前缀
     
      //每页显示多少条
      $page_num = $M->query("select conf_value from {$pre}config where conf_key='per.page.num'");
      $page_num = $page_num[0]['conf_value'];
      //dump($page_num);exit;
      //查询总记录数
      if(empty($where)){
          $sql = "select ds_date from (select ds_date  from `{$pre}register` union all select ds_date from `{$pre}appoint`) t group by ds_date ";
      }else{
          $sql = "select ds_date from (select ds_date  from `{$pre}register` union all select ds_date from `{$pre}appoint`) t where {$where} group by ds_date";
      }
      $count_list = $M->query($sql);
      $count = intval(count($count_list));

      //共多少页
      $pages = ceil($count/$page_num);
      //开始分页
      $in['per_page'] = $in['per_page'] - 1;
      $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];
      $offset = $in['per_page'] * $page_num;//偏移量
      if(empty($where)){
            $sql = "select {$field} from (select personpay_fee,card_type,ds_date, 'r' as `t`  from ss_register union all  select personpay_fee,card_type ,ds_date, 'a' as `t` from ss_appoint) t  group by date  order by date desc limit {$offset},{$page_num}"; 
            $rel= $M->query($sql);
  
      }else{
            $sql = "select {$field} from (select personpay_fee,card_type,ds_date, 'r' as `t`  from ss_register union all  select personpay_fee,card_type ,ds_date, 'a' as `t` from ss_appoint) t where {$where} group by date  order by date desc limit {$offset},{$page_num}";  
            $rel = $M->query($sql);
      }
     //dump($rel);exit;
      $rel_ist=array(); 
      $rel_list['code']='0';
      $rel_list['msg']='查询成功';
      $rel_list['data']['page']['total_row_num'] = $count;//总条数
      $rel_list['data']['page']['total_page_num'] = $pages;//总页数
      $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
      $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条
      $rel_list['data']['list']=$rel;
      $title_array = array('挂号金额','挂号笔数','今日挂号笔数','预约挂号笔数','医保挂号笔数','自费挂号笔数'); 
      $excel_list = $rel;
     
      //$export="1";
      if($in['export']=="1"){
           $excel_filename='挂号统计'; 
           $this->export($excel_list,$title_array,$excel_filename );
      }else{
           $this->ajaxReturn($rel_list,'JSON');
      }

  }


    /**
     * @desc 科室统计（今日挂号）  假数据
     * @param
     * @author 鲁翠霞
     * @fianal 2020-04-21
     */
    public function deptStatistics(){
      $rules = array(
        array('date',0, '/^d|m|y$/'),//d,m,y  天，月，年 
        array('start_time', 0, '/^\d{4}-\d{2}-\d{2}$/'),
        array('end_time', 0, '/^\d{4}-\d{2}-\d{2}$/'),
        array('per_page',0,'/^\d+$/'),//第几页
        array('export',0)//为1时，导出excel
      );
      $in = $this->validParam($rules);//入参处理
      $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
      $start_time = (!empty($in['start_time'])) ? $in['start_time'] : $in['start_time'];
      $end_time = (!empty($in['end_time'])) ? $in['end_time'] : '';
  
       $where = '1';
      if(!empty($start_time)){
        $where.=" and ds_date>='".$start_time."' ";
      }
      if(!empty($end_time)){
        $where.=" and ds_date<='".$end_time."'";
      }
      //$where = ltrim($where," and");
      if($in['date']=='d'){
        $field_date = "ds_date as date";
      }elseif($in['date']=='m'){
        $field_date = "left(ds_date,7) as date";
      }elseif($in['date']=='y'){
        $field_date = "left(ds_date,4) as date";
      }elseif($in['date']==''){
        $field_date = "ds_date as date";
      }
      $field="dept_name,count(id)as num,sum(personpay_fee) as fee,  {$field_date}";
      $field2="dept_name,count(id)as total_num,sum(personpay_fee) as total_fee, count(id)as num, count(id) as yy_num, count(id)as reg_num,  {$field_date}";
      $M = M();
      $pre = C('DB_PREFIX');//表前缀
     
      //每页显示多少条
      $page_num = $M->query("select conf_value from {$pre}config where conf_key='per.page.num'");
      $page_num = $page_num[0]['conf_value'];

      //开始分页
      $in['per_page'] = $in['per_page'] - 1;
      $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page']; 
      $offset = $in['per_page'] * $page_num;//偏移量

      //挂号量及金额
      $sql = "select {$field2} from `{$pre}register` where {$where} group by dept_name,date  order by date desc limit {$offset},{$page_num}";  
      $rel = $M->query($sql);
     

      // //查累计挂号量及金额 
      // $total_sql = "select dept_name,count(id)as num,sum(personpay_fee) as fee from `{$pre}register` group by dept_name  order by num desc ";  
      // $total_rel = $M->query($total_sql);

      //挂号量排名前5的科室
      $reg_num_sql =  "select {$field} from `{$pre}register` where {$where} group by dept_name,date  order by num desc limit 0,5";
      $reg_num_rel = $M->query($reg_num_sql);
      //dump($reg_num_rel);

      //金额量排名前5的科室
      $reg_fee_sql =  "select {$field} from `{$pre}register` where {$where} group by dept_name,date  order by fee desc limit 0,5";
      $reg_fee_rel = $M->query($reg_fee_sql);
     
      //查询总记录数
      $count = intval(count($rel));
      //共多少页
      $pages = ceil($count/$page_num);
     
      
      $rel_ist=array(); 
      $rel_list['code']='0';
      $rel_list['msg']='查询成功';
      $rel_list['data']['page']['total_row_num'] = $count;//总条数
      $rel_list['data']['page']['total_page_num'] = $pages;//总页数
      $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
      $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条
      $rel_list['data']['list']=$rel;

      $rel_list['data']['num_list']=$reg_num_rel;//挂号量排名前5的科室
      $rel_list['data']['fee_list']=$reg_fee_rel;//金额量排名前5的科室
      $title_array = array('科室','累计数量','累计金额','挂号量','预约挂号量','今日挂号量','时间');  
      $excel_list = $rel;
     
      //$export="1";
      if($in['export']=="1"){
           $excel_filename='科室统计'; 
           $this->export($excel_list,$title_array,$excel_filename );
      }else{
           $this->ajaxReturn($rel_list,'JSON');
      }

  }



     /**
     * @desc 总体数据统计  假数据
     * @param
     * @author 鲁翠霞
     * @fianal 2020-04-21
     */
    public function dataStatistics(){
      $rules = array(
        array('date',0, '/^d|m|y$/'),//d,m,y  天，月，年 
        array('start_time', 0, '/^\d{4}-\d{2}-\d{2}$/'),
        array('end_time', 0, '/^\d{4}-\d{2}-\d{2}$/'),
        array('per_page',0,'/^\d+$/'),//第几页
        array('export',0)//为1时，导出excel
      );
      $in = $this->validParam($rules);//入参处理
      $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
      $start_time = (!empty($in['start_time'])) ? $in['start_time'] : $in['start_time'];
      $end_time = (!empty($in['end_time'])) ? $in['end_time'] : '';
  
       $where = '1';
      if(!empty($start_time)){
        $where.=" and ds_date>='".$start_time."' ";
      }
      if(!empty($end_time)){
        $where.=" and ds_date<='".$end_time."'";
      }
      //$where = ltrim($where," and");
      if($in['date']=='d'){
        $field_date = "ds_date as date";
      }elseif($in['date']=='m'){
        $field_date = "left(ds_date,7) as date";
      }elseif($in['date']=='y'){
        $field_date = "left(ds_date,4) as date";
      }elseif($in['date']==''){
        $field_date = "ds_date as date";
      }
     
      $field2="count(id)as total_reg_num,sum(personpay_fee) as total_reg_fee, 
                count(id)as reg_num, sum(personpay_fee) as reg_fee ,count(id) as yy_num, 
                count(id)as total_trading_num,sum(personpay_fee) as total_trading_fee,
                count(id)as t_reg_num,sum(personpay_fee) as t_reg_fee,
                  {$field_date}";
      $M = M();
      $pre = C('DB_PREFIX');//表前缀
     
      //每页显示多少条
      $page_num = $M->query("select conf_value from {$pre}config where conf_key='per.page.num'");
      $page_num = $page_num[0]['conf_value'];

      //开始分页
      $in['per_page'] = $in['per_page'] - 1;
      $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page']; 
      $offset = $in['per_page'] * $page_num;//偏移量

      //
      $sql = "select {$field2} from `{$pre}register` where {$where} group by date  order by date desc limit {$offset},{$page_num}";  
      $rel = $M->query($sql);
  
 
      //近8天的挂号量
      $reg_num_sql =  "select count(id)as num,{$field_date} from `{$pre}register` where {$where} group by date  order by num desc limit 0,8";
      $reg_num_rel = $M->query($reg_num_sql);
      //dump($reg_num_rel);

      //近8天的交易量
      $reg_fee_sql =  "select sum(personpay_fee) as fee,{$field_date} from `{$pre}register` where {$where} group by date  order by fee desc limit 0,8";
      $reg_fee_rel = $M->query($reg_fee_sql);

      $dev_sql = "select count(id)as num,{$field_date} from `{$pre}register` where {$where} group by date  order by num desc limit 0,8";
      $dev_rel = $M->query($dev_sql);
      foreach($rel as $k=>$v){
        $rel[$k]['online_num']=120;
        $rel[$k]['total_dev_num']=120;
        $rel[$k]['offline']=0;
        $rel[$k]['abnormal']=0;
        $rel[$k]['turn_off']=0;
      }
     
      //查询总记录数
      $count = intval(count($rel));
      //共多少页
      $pages = ceil($count/$page_num);
     
      
      $rel_ist=array(); 
      $rel_list['code']='0';
      $rel_list['msg']='查询成功';
      $rel_list['data']['page']['total_row_num'] = $count;//总条数
      $rel_list['data']['page']['total_page_num'] = $pages;//总页数
      $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
      $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条
      $rel_list['data']['list']=$rel;
    

      $rel_list['data']['reg_list']=$reg_num_rel;//近8天的挂号量
      $rel_list['data']['business_list']=$reg_fee_rel;//近8天的业务金额
      $title_array = array('累计挂号量','累计挂号金额','挂号量','挂号金额','预约量','累计交易量','累计交易金额','今日挂号量','今日挂号金额','时间');   
      $excel_list = $rel;
      
      //$export="1";
      if($in['export']=="1"){
           $excel_filename='总体数据统计'; 
           $this->export($excel_list,$title_array,$excel_filename );
      }else{
           $this->ajaxReturn($rel_list,'JSON');
      }

        
    }


    /**
     * @desc 首页整体数据统计  
     * @param
     * @author 鲁翠霞
     * @fianal 2020-04-21
     */
    public function businessStatistics(){
      $rules = array(
        array('per_page',0,'/^\d+$/'),//第几页
       
      );
      $in = $this->validParam($rules);//入参处理
      $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
  
      $M = M();
      //////////////////挂号数据//////////////////
      //累计（挂号量，金额  ｜   预约挂号量，今日挂号量   ｜  医保量，自费量）
      $sql = " select sum(c.personpay_fee)as total_fee,  COUNT(c.personpay_fee) AS total_num ,
           COUNT(if(t='r',true,null)) AS total_jrgh_num ,COUNT(if(t='a',true,null)) AS total_yygh_num ,
           COUNT(if(card_type='1',true,null)) AS total_yb_num , COUNT(if   (card_type!='1',true,null)) AS total_zf_num 
           from  (select personpay_fee,card_type,ds_date ,'r' as t from ss_register 
           union all   select personpay_fee,card_type ,ds_date ,'a' as `t` from ss_appoint) c ";
      $return_total_reg_data = $M->query($sql);
      //预约挂号占比 
      $return_total_reg_data[0]['total_yygh_ratio'] = round($return_total_reg_data[0]['total_yygh_num']/$return_total_reg_data[0]['total_num']*100,2)."％";
      //今日挂号占比 
      $return_total_reg_data[0]['total_jrgh_ratio'] = round($return_total_reg_data[0]['total_jrgh_num']/$return_total_reg_data[0]['total_num']*100,2)."％";
      //医保占比
      $return_total_reg_data[0]['total_yb_ratio'] = round($return_total_reg_data[0]['total_yb_num']/$return_total_reg_data[0]['total_num']*100,2)."％";
      //自费占比 
      $return_total_reg_data[0]['total_zf_ratio'] = round($return_total_reg_data[0]['total_zf_num']/$return_total_reg_data[0]['total_num']*100,2)."％";

    
      //当日（挂号量，金额  ｜  预约挂号量，今日挂号量  ｜  医保量，自费量）
      $sql2 = " select sum(c.personpay_fee)as fee,  COUNT(c.personpay_fee) AS num ,
           COUNT(if(t='r',true,null)) AS jrgh_num ,COUNT(if(t='a',true,null)) AS yygh_num ,
           COUNT(if(card_type='1',true,null)) AS yb_num , COUNT(if   (card_type!='1',true,null)) AS zf_num 
           from  (select personpay_fee,card_type,ds_date ,'r' as t from ss_register 
           union all   select personpay_fee,card_type ,ds_date ,'a' as `t` from ss_appoint) c  where ds_date='".date('Y-m-d')."'";
      $return_today_reg_data = $M->query($sql2);
      //预约挂号占比 
      $return_today_reg_data[0]['yygh_ratio'] = round($return_today_reg_data[0]['yygh_num']/$return_today_reg_data[0]['num']*100,2)."％";
      //今日挂号占比 
      $return_today_reg_data[0]['jrgh_ratio'] = round($return_today_reg_data[0]['jrgh_num']/$return_today_reg_data[0]['num']*100,2)."％";
      //医保占比
      $return_today_reg_data[0]['yb_ratio'] = round($return_today_reg_data[0]['yb_num']/$return_today_reg_data[0]['num']*100,2)."％";
      //自费占比 
      $return_today_reg_data[0]['zf_ratio'] = round($return_today_reg_data[0]['zf_num']/$return_today_reg_data[0]['num']*100,2)."％";
   
      //上周同期挂号量，金额
      $sql3 = "select sum(c.personpay_fee)as last_week_fee,  COUNT(c.personpay_fee) AS last_week_num 
              from  (select personpay_fee ,ds_date from ss_register union all   select personpay_fee ,ds_date from ss_appoint) c   where ds_date='".date("Y-m-d",strtotime("-1 week"))."'";
      $return_last_week_reg_data = $M->query($sql3);
      //当日挂号量对比上周同期占比
      $return_today_reg_data[0]['today_num_ratio'] = round($return_today_reg_data[0]['num']/$return_last_week_reg_data[0]['last_week_num']*100,2)."％";
      //当日挂号金额对比上周同期占比
      $return_today_reg_data[0]['today_fee_ratio'] = round($return_today_reg_data[0]['fee']/$return_last_week_reg_data[0]['last_week_fee']*100,2)."％";

      $reg_data['statistics'] = array_merge($return_total_reg_data[0],$return_today_reg_data[0]);
      //dump($reg_data);


      //近8天的挂号数据 
      $sql4 = "select count(c.id)as num,ds_date as date from  (select id ,ds_date from ss_register   union all   select id ,ds_date from          ss_appoint)    c  where ds_date>='".date("Y-m-d",strtotime("-8 day"))."' and ds_date <= '".date("Y-m-d")."' GROUP BY ds_date order by ds_date desc";
      $eight_num_data['eight'] = $M->query($sql4);

      //挂号量排名前 5 的科室 
      $sql5 = "select count(c.dept_name)as num,dept_name from  (select dept_name from ss_register   union all   select dept_name from          ss_appoint) c  group by dept_name  order by num desc  limit 0,5";
      $return_dept_data['dept'] = $M->query($sql5);

      $rel['reg'] = array_merge($reg_data,$eight_num_data,$return_dept_data);



      ///////////////交易数据////////////

      //累计交易量，金额
      $sql6 = "select count(id) as total_num, sum(personpay_fee)as total_fee from ss_payment where pay_status='1'";
      $return_total_rading_data = $M->query($sql6);
      //当日交易量，金额 
      $sql7 = "select count(id) as today_num, sum(personpay_fee)as today_fee  from ss_payment where left(pay_time,10)='".date('Y-m-d')."'";
      $return_today_trading_data = $M->query($sql7);
      //上周同期挂号量，金额
      $sql8 = "select count(id) as last_week_num, sum(personpay_fee)as last_week_fee  from ss_payment where left(pay_time,10)='".date('Y-m-d',strtotime("-1 week"))."'";
      $return_last_week_trading_data = $M->query($sql8);
      //当日业务量对比上周同期占比
      $return_today_trading_data[0]['today_num_ratio'] =  round($return_today_trading_data[0]['today_num']/$return_last_week_trading_data[0]['last_week_num']*100,2)."％";
      //当日业务量对比上周同期占比
      $return_today_trading_data[0]['today_fee_ratio'] =  round($return_today_trading_data[0]['today_fee']/$return_last_week_trading_data[0]['last_week_fee']*100,2)."％";

      $trading_data['statistics'] = array_merge($return_total_rading_data[0],$return_today_trading_data[0]);

      //业务类型，支付方式，患者类型 占比 
      $sql9 = "select count(if(card_type=1,true,null)) as yb_num,   count(if(card_type!=1,true,null))as zf_num,  count(card_type) as card_type_num,count(pay_type) as pay_type_num, count(if(pay_type=1,true,null))as alipay_num, count(if(pay_type=2,true,null))as wechat_num, count(if(pay_type=3,true,null))as bank_num, count(if(business_type='jrgh'||business_type='yygh',true,null)) as reg_num,
      count(if(business_type='zzjf',true,null)) as fee_num,   count(if(business_type='zzjf'||business_type='jrgh'||business_type='yygh',true,null)) as bussiness_num from ss_payment";
      $return_trading_data = $M->query($sql9);
      $return_trading_data[0]['zf_ratio'] =  round($return_trading_data[0]['zf_num']/$return_trading_data[0]['card_type_num']*100,2)."％";
      $return_trading_data[0]['yb_ratio'] =  round($return_trading_data[0]['yb_num']/$return_trading_data[0]['card_type_num']*100,2)."％";
      $return_trading_data[0]['reg_ratio'] =  round($return_trading_data[0]['reg_num']/$return_trading_data[0]['bussiness_num']*100,2)."％";
      $return_trading_data[0]['fee_ratio'] =  round($return_trading_data[0]['fee_num']/$return_trading_data[0]['bussiness_num']*100,2)."％";
      $return_trading_data[0]['alipay_ratio'] =round($return_trading_data[0]['alipay_num']/$return_trading_data[0]['pay_type_num']*100,2)."％";
      $return_trading_data[0]['wechat_ratio'] =round($return_trading_data[0]['wechat_num']/$return_trading_data[0]['pay_type_num']*100,2)."％";
      $return_trading_data[0]['bank_ratio'] = round($return_trading_data[0]['bank_num']/$return_trading_data[0]['pay_type_num']*100,2)."％";

      $trading_data['ratio'] = $return_trading_data[0];
     // dump($return_trading_data);

      //近8天的交易数据 
      $sql10 = "select count(id) as num, left(pay_time,10) as date  from ss_payment  where left(pay_time,10)<='".date('Y-m-d')."' and left(pay_time,10)>='".date('Y-m-d',strtotime("-8 day"))."' group by date order by date desc";
      $trading_data['eight'] = $M->query($sql10);
      //挂号金额排名前 5 的科室 
      $sql11 = "select sum(c.personpay_fee)as fee,dept_name from  (select personpay_fee,dept_name from ss_register   union all 
        select personpay_fee ,dept_name from    ss_appoint) c group by dept_name  order by fee desc  limit 0,5";
      $trading_data['dept'] = $M->query($sql11);
      $rel['trading'] = $trading_data;


      ///////////////////建卡数据//////////////

      //累计建卡量，金额
      $sql12 = "select count(t1.id) as total_num, sum(t2.personpay_fee) as total_fee from ss_create as t1 left join ss_payment as t2 on t1.payment_id=t2.id";
      $return_total_create_data = $M->query($sql12);
     //当日建卡量，金额 
      $sql13 = "select count(t1.id) as today_num, sum(t2.personpay_fee) as today_fee from ss_create as t1 left join ss_payment as t2 on t1.payment_id=t2.id where left(t1.create_time,10)='".date('Y-m-d')."' ";
      $return_today_create_data = $M->query($sql13);
      //上周同期建卡量，金额  
      $sql13 = "select count(t1.id) as last_week_num, sum(t2.personpay_fee) as last_week_fee from ss_create as t1 left join ss_payment as t2 on t1.payment_id=t2.id where left(t1.create_time,10)='".date('Y-m-d',strtotime("-8 day"))."' ";
      $return_last_week_create_data = $M->query($sql13);
     //当日建卡对比上周同期占比
      $return_today_create_data[0]['today_num_ratio'] =  round($return_today_create_data[0]['today_num']/$return_last_week_create_data[0]['last_week_num']*100,2)."％";
      //当日建卡对比上周同期占比
      $return_today_create_data[0]['today_fee_ratio'] =  round($return_today_create_data[0]['today_fee']/$return_last_week_create_data[0]['last_week_fee']*100,2)."％";
      $rel['create'] = array_merge($return_total_create_data[0],$return_today_create_data[0]);


      ///////////自助机异常数据///////////
    
      $rel_list['code']='0';
      $rel_list['msg']='查询成功';
      // $rel_list['data']['page']['total_row_num'] = $count;//总条数
      // $rel_list['data']['page']['total_page_num'] = $pages;//总页数
      // $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
      // $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条
      $rel_list['data']=$rel;

      $this->ajaxReturn($rel_list,'JSON');
      

        
    }

    /**
     * @desc 科室查询  假数据 科室信息(包括一级和二级)
     * @param
     * @author 鲁翠霞
     * @fianal 2020-04-21
     */ 

    public function departmentInfo(){
      $rel = array ();
      $a = array('内科','骨科','神经科','眼科','康复科','皮肤科','美容科');
      $rel = array();
      for($i=1;$i<7;$i++){
        
        $rel[$i] = array('deptCode'=>"00".$i,
                          'deptName'=>$a[$i],
                  );
      
        for($n=1;$n<=3;$n++){
         
          $rel[$i]['second'][]= array(
              'deptCode'=>"0000".$n,
              'deptName'=>$a[$i].$n,
         );
        } 
      }
   
    
      $rel_list['code'] = '0';
      $rel_list['msg'] = '查询成功';
      $rel_list['data']['dept'] = $rel;
    
      $this->ajaxReturn($rel_list,'JSON');
    }


    
    public function departmentInfo1(){
      $zzjCode = I('post.zzjCode');
      $regFlag = I('post.regFlag');

        $rules = array(
            array('zzjCode', 1, '', '' ,''),
            array('regFlag', 1, '', '/^(1|2)$/',''),
        );
        $this->validate($rules);

        $startDate = date('Ymd');

      if($regFlag == 1)//挂号
      {
        $endDate = $startDate;
      }
      else if($regFlag == 1)//预约
      {
        $endDate = date('Ymd',strtotime("+ ".date('t')."day"));
      }
      //调HIS获取出诊科室列表
      Vendor("His");
      $HIS = new \His();
      $request = array(
        'startDate' => $startDate,
        'endDate' => $endDate,
        'regFlag' => $regFlag
      );
      $business = $HIS->GetVisitDepartments($request);

      $result = array(
        "code" => "000",
        "message" => "成功",
        "business" => $business
      );
      $this->ajaxReturn($result,"JSON");
    }


    //导出数据
    // 需要表名，表头
    public function export1(){
      //excel版本 
      $_POST['type'] = '07';
      $type = I('post.type');

      if(empty($type)){ 
        $type='07';
      }
      $rel = M('create')->field('zzj_code,card_type,name,create_time')->select();  
      //导入第三方类库
      vendor('PHPExcel.PHPExcel');
      //实例化PHPExcel这个类，相当于在桌面新建了一个Excel的文件
      $PHPExcel = new \PHPExcel();
      //获取当前活动的sheet
      $PHPSheet = $PHPExcel->getActiveSheet();
      //给当前活动的sheet重命名
      $PHPSheet -> setTitle('demo');
      //添加表头
      $code = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
      $list = array('自助机编号','卡类型','名字');
      foreach($list as $key=>$value){
        $PHPSheet -> setCellValue($code[$key].'1',$value);
      }
      //添加内容
      for($i=2;$i<=count($rel)+1;$i++){
        $j=0;
        foreach($rel[$i-2] as $key=>$val){
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
    }




}