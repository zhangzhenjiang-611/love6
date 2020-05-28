<?php
namespace Manage\Controller;
use Think\Controller;
use Org\Util\Rbac;
class IndexController extends CommonController  {
	public function index(){

		$this->display();
	}


	/**
     * @desc 全局配置
     * @param
     * @author 鲁翠霞
     * @final 2020-04-16
     */
	public function globalConfig(){
		 $rules = array(
            array('per_page_num',0),///后台每页显示记录条数
            array('heartbeat_interval',0),//心跳间隔时间单位秒
            array('appoint_charge_process',0,'/^GH|QH$/'),//是预约挂号还是预约取号收费,GH前者QH后者
            array('enabled_sfz_manual_input',0,'/^([0-1])$/'),//是否启用身份证手动输入
            array('enabled_szf_card',0,'/^([0-1])$/'),//是否支持读身份证
            array('enabled_yb_card',0,'/^([0-1])$/'),//是否支持读医保卡
            array('enabled_jyt_card',0,'/^([0-1])$/'),//是否支持读京医通卡
            array('enabled_fee_selected',0,'/^([0-1])$/'),//是否启用缴费清单可选1可选0不能只能缴全部
			array('enabled_dev_auto_refund',0,'/^([0-1])$/'),//是否启用自助机自动退款功能,用于当患者支付成功但his失败时候
			array('hos_name',0),//医院名
			array('hos_code',0),//医院编号
			array('hos_introduce',0),//医院介绍
			array('hos_logo',0),//医院logo
			array('ticketape_limit_num',0),//凭条补打次数限制,0表示无限制
			array('ticketape_limit_time',0),//凭条补打时间限制单位天,0表示无限制
			array('create_patient_fee',0),//建卡费用单位分
			array('pic_list',0),
        	);
		$in = $this->validParam($rules);//入参处理
	
		$M = M('config');
		$MB = M("banner");

		//$ws = web_server_url().__ROOT__.'/public';
		if(isset($in['pic_list'])){
			foreach($in['pic_list'] as $k => $v){
				$arr = array(
					'url' => $v['img_url'],
					'status' => $v['status']
				);
				 if(isset($v['id'])){
				 	$return = $MB->where("id='".$v['id']."'")->save($arr);
				 }else{
					$return = $MB->data($arr)->add();
				}
			}
		}
		//由于是两张表，处理完轮播图片后删除数据 
		unset($in['pic_list']);

		foreach($in as $k => $v){
			$k = str_replace("_",".",$k);
			$arr = array(
				'conf_value' => $v,
				'default_val' => $v,
			);	
	
			if($k == 'hos.logo'){
				$return = $M->where("conf_key ='".$k."'")->field("conf_value")->find();					
				$url = explode( "public",$return['conf_value']);
				//删除原有图片
				unlink('public/'.$url[1]);
				//unlink(__ROOT__.'/Public/'.$return['conf_value']);

			}
			$rel = $M->where("conf_key='".$k."'")->save($arr); // 根据条件更新记录
		}
		
		$rel_list['code'] = '0';
		$rel_list['msg'] = '修改成功'; 

		$this->ajaxReturn($rel_list,'JSON');
	}




	/**
     * @desc 删除首页轮播图  
     * @param
     * @author 鲁翠霞
     * @final 2020-04-26
     */
	public function delBanner(){
		//1是必传参数
		$rules = array(
			array('id',0),
			array('banner_url',0),
		);
		$in = $this->validParam($rules);//入参处理
		$url = explode( "public",$in['banner_url']);
		$M = M("banner");
		if(empty($in['id'])){	
			unlink('public/'.$url[1]);
			$rel=array(
					'code'=>'0',
					'msg'=>'数据删除成功!',
			);
		}else{
			$return_img = $M->where("id ='".$in['id']."'")->field("url")->find();	
			$url = explode( "public",$return_img['url']);
			//删除原有图片
			unlink('public/'.$url[1]);
			//unlink(__ROOT__.'/Public/'.$return_img['url']);
			$return = $M->where("id='".$in['id']."'")->delete(); // 根据条件更新记录
		
			if($return>0){
				$rel=array(
					'code'=>'0',
					'msg'=>'数据删除成功!',
				);
			}else{
				$rel=array(
					'code'=>'1', 
					'msg'=>'数据删除失败!',
				);
			}
		}
		
	
		
		$this->ajaxReturn($rel,'JSON');
	}



	/** 
     * @desc 修改首页轮播图
     * @param
     * @author 鲁翠霞
     * @final 2020-04-26
     */
	public function updBanner(){
		$rules = array(
			array('id',1),
			array('status',0),
        );
		$in = $this->validParam($rules);//入参处理

		$arr = array(
			"status"=>$in['status']
		);
		$return = M("banner")->where("id='".$in['id']."'")->save($arr); // 根据条件更新记录
		$rel['code'] = '0';
		$rel['msg'] = '数据修改成功!';
		
		$this->ajaxReturn($rel,'JSON');

	}
 
	
	/**
     * @desc 查询首页轮播图
     * @param
     * @author 鲁翠霞
     * @final 2020-04-26
     */
	public function selBanner(){
		$rel_list=M('banner')->field('id,url as img_url,status')->select();
		foreach ($rel_list as $key => $value) {
			$rel_list[$key]['img_url'] = web_server_url().__ROOT__.'/public/'.$value['img_url'];
		}
		if($rel_list>0){
			$rel['code']='0';
			$rel['msg']='';
			$rel['data']=$rel_list;
		}else{
			$rel['code']='1';
			$rel['msg']='数据未查询，请重新查询。';
			$rel['data']="";
		}
	
		//dump($rel);exit;
		$this->ajaxReturn($rel,'JSON');



	}	

	/**
     * @desc 查询全局配置
     * @param
     * @author 鲁翠霞
     * @final 2020-04-19
     */
	public function selConfig(){
		
		$rel_list = M('config')->field('conf_key,conf_value')->select();
		$banner_list = M('banner')->field('id,url as img_url,status')->select();
		// foreach ($banner_list as $key => $value) {
		// 	$banner_list[$key]['img_url'] = web_server_url().__ROOT__.'/public/'.$value['img_url'];
		// }
		$new_rel_list = array();
		foreach($rel_list as $k=>$v){
			$rel_list[$k]['conf_key'] = str_replace(".","_",$v['conf_key']);
			$new_rel_list[$rel_list[$k]['conf_key']]	= $rel_list[$k]['conf_value'];
		}
		if($rel_list>0){
			$rel['code'] = '0';
			$rel['msg'] = '';
			$rel['data']['config'] = $new_rel_list;
			$rel['data']['banner'] = $banner_list;
		}else{
			$rel['code'] = '1';
			$rel['msg'] = '数据未查询，请重新查询。';
			$rel['data'] = "";
		}
	
		//dump($rel);exit;
		$this->ajaxReturn($rel,'JSON');



	}	

	

	/**
     * @desc 上传图片
     * @param
     * @author 鲁翠霞
     * @final 2020-04-17
     */
	public function upload(){
		$dir="./Public/img/upload/";
		if(!file_exists($dir)){
			mkdir ($dir,0777,true);
		}
		$upload = new \Think\Upload();// 实例化上传类
		//dump($upload);exit;
		$upload->maxSize   =     3145728 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->rootPath  =     $dir; // 设置附件上传根目录
		$upload->savePath  =     ''; // 设置附件上传（子）目录
		// 上传文件 
		$info   =   $upload->upload();
		foreach($info as $k=>$v){
			$path= web_server_url().__ROOT__.ltrim(strtolower($dir),'.').$info[$k]['savepath'].$info[$k]['savename'];
		}
		//$path=__ROOT__.'/public/img/upload/'.$info['photo']['savepath'].$info['photo']['savename'];	
		if(is_array($info)) {// 上传错误提示错误信息		
			$rel['code'] = '0';
			$rel['msg'] = '上传成功!';
			$rel['data']['img_url'] = $path;	
		}else{// 上传成功
			$rel['code'] = '0';
			$rel['msg'] = '上传失败!';	
		}
		//dump($rel);exit;
		$this->ajaxReturn($rel,'JSON');
	}


	/**
     * @desc 业务模块配置
     * @param
     * @author 鲁翠霞
     * @final 2020-04-17
     */
	public function addModule(){
		//1是必传参数
		$rules = array(
            array('name',1),//模块名称
            array('index',1),//模块英文jrgh,yygh,yyqh,zzjf,zzjk,hyddy,zhcx
            array('sort',1),//排序
            array('description',0),//配置说明  
        );
		$in = $this->validParam($rules);//入参处理
		$arr = array(
			'name'=>$in['name'],
			'index'=>$in['index'],
			'sort'=>$in['sort'],
			'description'=>$in['description'],
		);
		$M = M('module');
		//查看数据是否存在	
		$return = $M->where("name='".$in['name']."'")->field("name")->find();
		$rel = array();
		if(count($return)>0){
			$rel['code'] = '1';
			$rel['msg'] = '模块已存在,请修改或新增其它模块!';
		}else{
			$re_add = $M->data($arr)->add();
			if($re_add){
				$rel['code'] = '0';
				$rel['msg'] = '数据添加成功!';
			}else{
				$rel['code'] = '1';
				$rel['msg'] = '数据添加失败!';	
			}
		}
		$this->ajaxReturn($rel,'JSON');
	
	}	

	/**
     * @desc 删除业务模块   
     * @param
     * @author 鲁翠霞
     * @final 2020-04-19
     */
	public function delModule(){
		//1是必传参数
		$rules = array(
            array('id_list',1),
		);
		$in = $this->validParam($rules);//入参处理
		foreach($in['id_list'] as $k=>$v){
			if($v['id']=='all'){
				$return = M("module")->where('1')->delete(); 
			}else{
				$return = M("module")->where("id='".$v['id']."'")->delete(); // 根据条件更新记录
			}
		}
		$rel=array(
			'code'=>'0',
			'msg'=>'数据删除成功!',
		);
		
		$this->ajaxReturn($rel,'JSON');
	}


	/**
     * @desc 修改业务模块    
     * @param
     * @author 鲁翠霞
     * @final 2020-04-19
     */
	public function updModule(){
		//1是必传参数
		$rules = array(
			array('id',1),
			array('name',1),//模块名称
			array('index',1),//模块英文jrgh,yygh,yyqh,zzjf,zzjk,hyddy,zhcx
			array('sort',1),//排序
			array('description',0),//配置说明  
		);
		$in = $this->validParam($rules);//入参处理
		$arr = array(
			'name'=>$in['name'],
			'index'=>$in['index'],
			'sort'=>$in['sort'],
			'description'=>$in['description'],
		);
		$M = M('module');	
		$rel = $M->where("id='".$in['id']."'")->select();
		if(count($rel)==1){
			$return = $M->where("id='".$in['id']."'")->save($arr); // 根据条件更新记录
			$rel = array(
				'code'=>'0',
				'msg'=>'数据修改成功!',
			);
		}else{
			$rel = array(
				'code'=>'1',
				'msg'=>'数据修改失败!',
			);
		}
		$this->ajaxReturn($rel,'JSON');
	}	

	/**
     * @desc 查询业务模块    
     * @param
     * @author 鲁翠霞
     * @final 2020-04-19
     */
	public function selModule(){
		//1是必传参数
		$rules = array(
			array('id',0),
			array('dev_id',1),//机器id,0代表所有机器 
		);
		$in = $this->validParam($rules);//入参处理
		$M = M('module');	
		$Md = M('dev_mod');	
		$field = 'id,name,index,sort,description';
		if(empty($in['id'])){
			$rel = $M->field($field)->select();
		}else{
			$rel = $M->where("id='".$in['id']."'")->field($field)->select();
		}

		foreach($rel as $k=>$v){
			$rel[$k]['img_url'] = web_server_url().__ROOT__.'/public/img/business_module/'.$v['index'].'.png';
			$rel2 = $Md->where("mod_id='".$v['id']."' and dev_id='".$in['dev_id']."'")->field("is_disabled,service_start_time,service_end_time")->select();
			if(count($rel2)==0){
				$rel2 = $Md->where("mod_id='0' and dev_id='0'")->field("is_disabled,service_start_time,service_end_time")->select();
			}
			$rel[$k]['service_start_time'] = $rel2[0]['service_start_time'];
			$rel[$k]['service_end_time'] = $rel2[0]['service_end_time'];
			$rel[$k]['is_disabled'] = $rel2[0]['is_disabled'];			
		}


		$rel_list['code']='0';
		$rel_list['msg']='查询成功';
		$rel_list['data']=$rel;
	
		$this->ajaxReturn($rel_list,'JSON');

	}



	/**
     * @desc 支付渠道配置新增   
     * @param
     * @author 鲁翠霞
     * @final 2020-04-19
     */
	public function addMoudlePay(){
		//1是必传参数
		$rules = array(
            array('dev_list',0),//模块id
            array('pay_id',1),//支付id
            array('scan_code_type',0),//b扫c/c扫b
            array('pay_type',0),//支付方式  支付方式:1:支付宝;2:微信;3:支付宝+微信
        );
		$in = $this->validParam($rules);//入参处理
		$M = M('pay_mod');
		$where = "pay_id='".$in['pay_id']."' ";

		$return = $M->where($where)->field("id")->find();
		if(!empty($return)){
			$return = $M->where($where)->delete(); // 根据条件更新记录
		}
		
		if(!empty($in['dev_list'])){
				foreach($in['dev_list'] as $k=>$v){
				$mod_list .= $v['id'].","; 
			}
			$mod_list = rtrim($mod_list, ',');
			$arr = array(
					'mod_list'=>$mod_list,
					'pay_id'=>$in['pay_id'],
					'scan_code_type'=>$in['scan_code_type'],
					'description'=>$in['description'],
					'pay_type'=>$in['pay_type'],
				);
		
			$re_add = $M->data($arr)->add();
			
			if($re_add){
				$rel['code'] = '0';
				$rel['msg'] = '数据添加成功!';
			}else{
				$rel['code'] = '1';
				$rel['msg'] = '数据添加失败!';
			}
		}else{
				$rel['code'] = '0';
				$rel['msg'] = '数据修改成功!';

		}
		
		
		$this->ajaxReturn($rel,'JSON');

	} 


	/**
     * @desc 支付渠道配置查询（支付渠道里的配置支付模块）  111111111111
     * @param
     * @author 鲁翠霞
     * @final 2020-04-19
     */
	public function selMoudlePay(){
		//1是必传参数
		$rules = array(
            array('pay_id',1),//支付方式 
        );
		$in = $this->validParam($rules);//入参处理

		$M = M('pay_mod');
		$pre = C('DB_PREFIX');//表前缀
		$rel_moud = M('module')->field('id,name')->select();
	
		$data = array();
		foreach($rel_moud as $value){
			$pay_mod_list = $M->field("mod_list,scan_code_type")->where(" find_in_set('".$value['id']."',mod_list) and pay_id='".$in['pay_id']."'")->select();
	
			if($pay_mod_list){
				$value['status'] = 1;
				//$value['scan_code_type'] = $pay_mod_list[0]['scan_code_type'];
			}else{
				$value['status'] = 0;
				//$value['scan_code_type'] = $pay_mod_list[0]['scan_code_type'];
			}
			$data[]=$value;
			
		}
		$scan_list = $M->field("scan_code_type,pay_type")->where(" pay_id='".$in['pay_id']."'")->select();
		$rel['code'] = '0';
		$rel['msg'] = '查询成功!';
		$rel['data']['list'] = $data;
		$rel['data']['scan_code_type'] =  $scan_list[0]['scan_code_type'];
		$rel['data']['pay_type'] =  $scan_list[0]['pay_type'];
		$this->ajaxReturn($rel,'JSON');

	} 

	


	/**
     * @desc 查询支付模块  
     * @param
     * @author 鲁翠霞
     * @final 2020-04-19
     */ 
	public function selPayMoudle(){
		$M = M('payment_module');	
		$rel = $M->field('id,name,c_name,state,img_url')->select();
		foreach($rel as $k=>$v){
			$rel[$k]['img_url']=web_server_url().__ROOT__.$v['img_url'];
		}
		$rel_list['code'] = '0';
		$rel_list['msg'] = '查询成功';
		$rel_list['data'] = $rel;
	
		$this->ajaxReturn($rel_list,'JSON');

	} 

	/**
     * @desc 修改支付模块状态   
     * @param
     * @author 鲁翠霞
     * @final 2020-04-19
     */
	public function updPayMoudle(){ 
		//1是必传参数
		$rules = array(
			array('id',1),
			array('state',1),
		);
		$in = $this->validParam($rules);//入参处理
		$arr = array(
			'state'=>$in['state'],
		);
		$M = M('payment_module');	
		$return = $M->where("id='".$in['id']."'")->save($arr); // 根据条件更新记录
		$rel = array(
			'code'=>'0',
			'msg'=>'数据修改成功!',
		);
		$this->ajaxReturn($rel,'JSON');
	}	

	
	/**
     * @desc 设置功能模块  
     * @param 
	 * @param dev_id对应 ss_device的id
	 * @param mod_id对应 ss_module的id
	 * @param sparam ervice_date  11111111   要是这个值，最后一个也是1就对应ss_holiday
     * @author 鲁翠霞
     * @final 2020-04-20
     */
	public function setDevMod(){
		//1是必传参数
		$rules = array(
			array('dev_list',1),
			// array('dev_list',1),//自助机设备ID,0表示所有自助机
			// array('mod_list',1),//模块ID,0表示所有模块 
			// array('is_disabled',1),//是否停用:1是0否
			// array('service_date',1),//长度8位分别代表周一到周日、节假日;位值1选中,0未选中 周一到周日 11111110   周一到周五  11111000 
			// array('service_start_time',1),//服务开始时间
			// array('service_end_time',1),//服务结束时间
			// array('disabled_type',1),//停用表现形式,1隐藏模块2点击模块弹框3置灰
            // array('msg',1),//如果disabled_type为2时,弹框提示信息
           
        );
		$in = $this->validParam($rules);//入参处理
		$in['operator_id'] = empty($$_SESSION['uid']) ? '0' : $_SESSION['uid'];
		//dump($in);exit;
		$M = M('dev_mod');
		foreach($in['dev_list'] as $key=>$val){
			foreach($val['dev'] as $key1=>$val1){
				foreach($val['mod_list'] as $key2=>$val2){
					//删除原来的数据 
					$return = $M->where("dev_id='".$val1['id']."' and  mod_id='".$val2['id']."' ")->delete();
					$sql="INSERT INTO `ss_dev_mod` (`dev_id`,`mod_id`,`is_disabled`,`service_date`,`service_start_time`,`service_end_time`,`disabled_type`,`msg`,`operator_user`) VALUES ('".$val1['id']."','".$val2['id']."','".$val['is_disabled']."',b'".$val['service_date']."','".$val['service_start_time']."','".$val['service_end_time']."','".$val['disabled_type']."','".$val['msg']."','".$in['operator_id']."')";	
					$re_add = $M->execute($sql);	
				}
			}
		}
		if($re_add>0){
			$rel=array(
				'code'=>'0',
				'msg'=>'模块配置成功!',
			);
		}else{
			$rel=array(
				'code'=>'1',
				'msg'=>'模块配置失败!', 
			);	
		}
	
		$this->ajaxReturn($rel,'JSON');
		
	}	

	





}