<?php
namespace Manage\Controller;
use Think\Controller;
class LogController extends CommonController {
    public function index(){
		$this->display();
    }
    
    /**
     * @desc 指令日志查询
     * @param
     * @author 鲁翠霞
     * @final 2020-04-16
     */
    public function selCommandLog(){
        //1是必传参数
        $rules = array( 
            array('dev_code',0),//自助机编号
            array('command_id',0),//指令id
            array('operator_user_id',0),//操作员id
            array('start_date',0,'/^\d{4}-\d{2}-\d{2}$/'),//操作日期 
            array('end_date',0,'/^\d{4}-\d{2}-\d{2}$/'),//操作日期
            array('per_page',0,'/^\d+$/'),//第几页
        );
        $in = $this->validParam($rules);//入参处理
        $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
        $in['start_date'] = (!empty($in['start_date']) ? $in['start_date'] : date('Y-m-d')).' 00:00:00';
        $in['end_date'] = (!empty($in['end_date']) ? $in['end_date'] : date('Y-m-d')).' 23:59:59';
        $where = '';
        if(!empty($in['start_date'])){
            $where .= " and operation_time >= '".$in['start_date']."'";
          }
        if(!empty($in['end_date'])){
            $where .= " and operation_time<='".$in['end_date']."'";
        }
        if(!empty($in['dev_code'])){
          $where .= " and dev_code = '".$in['dev_code']."'";
        }
      
        if(!empty($in['command_id'])){
          $where .= " and command_id = '".$in['command_id']."'";
        }
        if(!empty($in['operator_user_id'])){
          $where .= " and operator_user_id = '".$in['operator_user_id']."'";
        }
        $where = ltrim($where," and");
        $M = M('command_log');
        $Mc = M('config');
        //$pre = C('DB_PREFIX');//表前缀
        //每页显示多少条
        $page_num = $Mc->field("conf_value")->where("conf_key='per.page.num'")->select();
        $page_num = $page_num[0]['conf_value'];
        //查询总记录数
        $count = $M->field('count(*) as tp_cnt')->where($where)->select();
        $count = intval($count[0]['tp_cnt']);
        //共多少页
        $pages = ceil($count/$page_num);
        //开始分页
        $in['per_page'] = $in['per_page'] - 1;
        $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];

        $offset = $in['per_page'] * $page_num;//偏移量
        $result = array();
        $result['code'] = '0';
        $result['msg'] = '查询成功';

        $result['data']['page']['total_row_num'] = $count;//总条数
        $result['data']['page']['total_page_num'] = $pages;//总页数
        $result['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
        $result['data']['page']['page_row_num'] = $page_num;//每页多少条
  
        $return_data = $M->field('dev_code,command_name,seq,operator,operation_time')->where($where)->order('operation_time desc')->limit($offset.','.$page_num)->select();
        $result['data']['list'] = $return_data;

		$this->ajaxReturn($result,'JSON');
    }


    //新增硬件故障日志
    public function Add_hflog(){
        //测试数据
        $_POST['zzj_code'] = 'zzj001';
        $_POST['hard_code'] = '1';
        $_POST['hard_type'] = '8';
        $_POST['hard_brand'] = '2222';
        $_POST['hard_time'] = date('Y-m-d H:i:s');
        $_POST['hard_error'] = '222';
        $_POST['is_solve'] = '0';
        $_POST['solve_man'] = 'xx';
        $_POST['solve_time'] = date('Y-m-d H:i:s');

        $zzj_code=trim(I('post.zzj_code')); //自助机编号
        $hard_code=trim(I('post.hard_code'));//硬件编号
        $hard_type= trim(I('post.hard_type'));//硬件种类:1凭条打印机2化验单打印机3(医保|京医学通卡)读卡器4银联打卡器5身份证读卡区6扫码枪7扫码墩8就诊卡读卡区
        $hard_brand= trim(I('post.hard_brand'));//硬件品牌
        $hard_time= trim(I('post.hard_time'));//硬件故障时间
        $hard_error= trim(I('post.hard_error'));//硬件错误信息
        $is_solve= trim(I('post.is_solve'));//是否解决:1解决0未解决
        $solve_man= trim(I('post.solve_man'));//解决人
        $solve_time= trim(I('post.solve_time'));//解决时间
        $create_time= date('Y-m-d H:i:s');
        $modified_time= date('Y-m-d H:i:s');
        $arr=array(
            'zzj_code'=>$zzj_code,
            'hard_code'=>$hard_code,
            'hard_type'=>$hard_type,
            'hard_brand'=>$hard_brand,
            'hard_time'=>$hard_time,
            'hard_error'=>$hard_error,
            'is_solve'=>$is_solve,
            'solve_man'=>$solve_man,
            'solve_time'=>$solve_time,
            'create_time'=>$create_time,
            'modified_time'=>$modified_time,
        );
         
        //查看是否接受到POST数据		
        if($_POST){
                $re_add=M('hflog')->data($arr)->add();
                if($re_add){
                    $rel=array(
                        'code'=>'0',
                        'msg'=>'数据添加成功!',
                    );
                }else{
                    $rel=array(
                        'code'=>'1',
                        'msg'=>'数据添加失败!',
                    );	
                }   
            //dump($rel);exit;
            $rel=json_encode($rel);
            $this->ajaxReturn($rel,'JSON');
        }
   }
    //硬件故障日志
    public function Sel_hflog(){
        //测试数据
		$_POST['zzj_code'] = 'zzj001';
		$operator=trim(I('post.zzj_code'));
		if(empty($zzj_code)){
			$rel=M('hflog')->select();
		}else{
			$rel=M('hflog')->where("zzj_code='".$zzj_code."'")->order('modified_time desc')->select();
        }
        foreach($rel as $k=>$v){
            //是否解决:1解决0未解决
            if($v['is_solve']=='1'){
                $rel[$k]['is_solve']='已解决';
            }elseif($v['is_solve']=='0'){
                $rel[$k]['is_solve']='未解决';
            }
        }
		//dump($rel);exit;
		$rel=json_encode($rel);
		$this->ajaxReturn($rel,'JSON');
    }

    
    //新增化验单打印日志
    public function Add_laboratory_log(){
        //测试数据
        $_POST['patient_id'] = 'zzj001';
        $_POST['input'] = '11';
        $_POST['output'] = '11';

        $patient_id=trim(I('post.patient_id')); //患者ID
        $input=trim(I('post.paint_str'));//输入字符串
        $output= trim(I('post.output'));//输出字符串
        $modified_time= date('Y-m-d H:i:s');
        $arr=array(
            'patient_id'=>$patient_id,
            'input'=>$input,
            'output'=>$output,
            'modified_time'=>$modified_time,
        );
         
        //查看是否接受到POST数据		
        if($_POST){
                $re_add=M('laboratory_log')->data($arr)->add();
                if($re_add){
                    $rel=array(
                        'code'=>'0',
                        'msg'=>'数据添加成功!',
                    );
                }else{
                    $rel=array(
                        'code'=>'1',
                        'msg'=>'数据添加失败!',
                    );	
                }   
            //dump($rel);exit;
            $rel=json_encode($rel);
            $this->ajaxReturn($rel,'JSON');
        }
   }
    //化验单打印日志
    public function Sel_laboratory_log(){
         //测试数据
		$_POST['patient_id'] = '';
		$patient_id=trim(I('post.patient_id'));
		if(empty($patient_id)){
			$rel=M('laboratory_log')->order('modified_time desc')->select();
		}else{
			$rel=M('laboratory_log')->where("patient_id='".$patient_id."'")->order('modified_time desc')->select();
        }

		//dump($rel);exit;
		$rel=json_encode($rel);
		$this->ajaxReturn($rel,'JSON');
    } 


    //新增打印凭条日志表用于补打
    public function Add_paint_log(){
         //测试数据
         $_POST['patient_id'] = 'zzj001';
         $_POST['paint_str'] = '111';

         $patient_id=trim(I('post.patient_id')); //患者ID
         $paint_str=trim(I('post.paint_str'));//拼接好的打印字符串
         $create_time= date('Y-m-d H:i:s');
         $modified_time= date('Y-m-d H:i:s');
         $arr=array(
             'patient_id'=>$patient_id,
             'paint_str'=>$paint_str,
             'create_time'=>$create_time,
             'modified_time'=>$modified_time,
         );
          
         //查看是否接受到POST数据		
         if($_POST){
             //查看数据是否存在
             $return=M('paint_log')->where("paint_str='".$paint_str."'")->field("paint_str")->find();
             if(!empty($return)){
                 $rel=array(
                     'code'=>'1',
                     'msg'=>'数据已存在,请修改或新增其它类型!',
                 );
             }else{
                 $re_add=M('paint_log')->data($arr)->add();
                 if($re_add){
                     $rel=array(
                         'code'=>'0',
                         'msg'=>'数据添加成功!',
                     );
                 }else{
                     $rel=array(
                         'code'=>'1',
                         'msg'=>'数据添加失败!',
                     );	
                 }
             }
             //dump($rel);exit;
             $rel=json_encode($rel);
             $this->ajaxReturn($rel,'JSON');
         }
    }
    //打印凭条日志表用于补打
    public function Sel_paint_log(){
        //测试数据
        $_POST['patient_id'] = '';
        $patient_id=trim(I('post.patient_id'));
        if(empty($patient_id)){
            $rel=M('paint_log')->order('modified_time desc')->select();
        }else{
            $rel=M('paint_log')->where("patient_id='".$patient_id."'")->order('modified_time desc')->select();
        }

        //dump($rel);exit;
        $rel=json_encode($rel);
        $this->ajaxReturn($rel,'JSON');
    }


    //新增自助机前端操作日志表按年月分表
    public function Add_frentend_oplog(){
        //测试数据
        $_POST['zzj_code'] = 'zzj001';
        $_POST['op_name'] = '111';
        $_POST['op_content'] = '11';        
        
        $zzj_code=trim(I('post.zzj_code')); //自助机编号
        $op_name=trim(I('post.op_name'));//操作名称
        $op_content= trim(I('post.op_content'));//操作内容
        $op_time= date('Y-m-d H:i:s');
        $create_time= date('Y-m-d H:i:s');
        $modified_time= date('Y-m-d H:i:s');
        $arr=array(
            'zzj_code'=>$zzj_code,
            'op_name'=>$op_name,
            'op_content'=>$op_content,
            'op_time'=>$op_time,
            'create_time'=>$create_time,
            'modified_time'=>$modified_time,
        );
         
        //查看是否接受到POST数据		
        if($_POST){
            //查看数据是否存在
            $return=M('frentend_oplog202002')->where("op_content='".$op_content."'")->field("op_content")->find();
            if(!empty($return)){
                $rel=array(
                    'code'=>'1',
                    'msg'=>'数据已存在,请修改或新增其它类型!',
                );
            }else{
                $re_add=M('frentend_oplog202002')->data($arr)->add();
                if($re_add){
                    $rel=array(
                        'code'=>'0',
                        'msg'=>'数据添加成功!',
                    );
                }else{
                    $rel=array(
                        'code'=>'1',
                        'msg'=>'数据添加失败!',
                    );	
                }
            }
            //dump($rel);exit;
            $rel=json_encode($rel);
            $this->ajaxReturn($rel,'JSON');
        }
   }
    //自助机前端操作日志表按年月分表
    public function Sel_frentend_oplog(){
        //测试数据
        $_POST['zzj_code'] = '';
        $zzj_code=trim(I('post.zzj_code'));
        if(empty($zzj_code)){
            $rel=M('frentend_oplog202002')->order('modified_time desc')->select();
        }else{
            $rel=M('frentend_oplog202002')->where("zzj_code='".$zzj_code."'")->order('modified_time desc')->select();
        }
        //dump($rel);exit;
        $rel=json_encode($rel);
        $this->ajaxReturn($rel,'JSON');
    }
    

    //新增后台系统操作日志表
    public function Add_oplog(){
         //测试数据
         $_POST['zzj_code'] = 'zzj001';
         $_POST['op_name'] = '111';
         $_POST['op_content'] = '11';        
         
         $zzj_code=trim(I('post.zzj_code')); //自助机编号
         $op_name=trim(I('post.op_name'));//操作名称
         $op_content= trim(I('post.op_content'));//操作内容
         $op_time= date('Y-m-d H:i:s');
         $create_time= date('Y-m-d H:i:s');
         $modified_time= date('Y-m-d H:i:s');
         $arr=array(
             'zzj_code'=>$zzj_code,
             'op_name'=>$op_name,
             'op_content'=>$op_content,
             'op_time'=>$op_time,
             'create_time'=>$create_time,
             'modified_time'=>$modified_time,
         );
          
         //查看是否接受到POST数据		
         if($_POST){
             //查看数据是否存在
             $return=M('oplog')->where("op_content='".$op_content."'")->field("op_content")->find();
             if(!empty($return)){
                 $rel=array(
                     'code'=>'1',
                     'msg'=>'数据已存在,请修改或新增其它类型!',
                 );
             }else{
                 $re_add=M('oplog')->data($arr)->add();
                 if($re_add){
                     $rel=array(
                         'code'=>'0',
                         'msg'=>'数据添加成功!',
                     );
                 }else{
                     $rel=array(
                         'code'=>'1',
                         'msg'=>'数据添加失败!',
                     );	
                 }
             }
             //dump($rel);exit;
             $rel=json_encode($rel);
             $this->ajaxReturn($rel,'JSON');
         }
    }
    //后台系统操作日志表
    public function Sel_oplog(){
        //测试数据
        $_POST['zzj_code'] = '';
        $zzj_code=trim(I('post.zzj_code'));
        if(empty($zzj_code)){
            $rel=M('oplog')->order('modified_time desc')->select();
        }else{
            $rel=M('oplog')->where("zzj_code='".$zzj_code."'")->order('modified_time desc')->select();
        }
        //dump($rel);exit;
        $rel=json_encode($rel);
        $this->ajaxReturn($rel,'JSON');
    }
  
}