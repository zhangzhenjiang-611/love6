<?php
namespace Manage\Controller;
use Think\Controller;
class ManController extends CommonController {
    public function index(){
		$this->display();
    }

    //打印机状态查询
    public function selPrinter(){
        //测试数据
		$_POST['id'] = '1';
		$id=trim(I('post.id'));
		if(empty($id)){
			$rel=M('printer_state')->select();
		}else{
			$rel=M('printer_state')->where("id='".$id."'")->select();
        }
        foreach($rel as $k=>$v){
			if($v['type']==1){
                $rel[$k]['type']="216";
            }else{
                $rel[$k]['type']="K80"; 
            }
		}
		//dump($rel);exit;
		
		$this->ajaxReturn($rel,'JSON');
    }

    //打印机状态删除
    public function delPrinter(){
       //测试数据
		$_POST['id'] = '1';
		$id=trim(I('post.id'));
		$id=explode(',',$id);
		if($_POST){
			//删除多条
			foreach($id as $k=>$v){
				$return=M("printer_state")->where("id='".$v."'")->delete(); // 根据条件更新记录
			}
			if($return>0){
				$rel=array(
					'code'=>'0',
					'msg'=>'数据删除成功!',
				);
			}else{
				$rel=array(
					'code'=>'1',
					'msg'=>'数据已删除!',
				);
			}
		}
		//dump($rel);exit;
		$rel=json_encode($rel);
		$this->ajaxReturn($rel,'JSON');
    }
    


    //自助机类型添加 1
    public function addDevType(){
        //测试数据
		// $_POST['code'] = '2';
		// $_POST['name'] = '32寸';
		// $_POST['system_version'] = '32windows';
        // $_POST['description'] = 'dasd';
        // $_POST['remark'] = ' ';

        //1是必传参数
        $rules = array(
            array('code',1),//类型编号
            array('name',1),//类型名称
            array('system_version',1),//系统版本
            array('description',0),//描述（描述类型详细配置等信息）
            array('remark',0),//备注
        );
        $in = $this->validParam($rules);//入参处理
        $create_time= date("Y-m-d H:i:s");//创建时间
        $modified_time= date("Y-m-d H:i:s");//更新时间
		$arr=array(
			'code'=>$in['code'],
			'name'=>$in['name'],
			'system_version'=>$in['system_version'],
            'description'=>$in['description'],
            'remark'=>$in['remark'],
            'create_time'=>$create_time,
            'modified_time'=>$modified_time,
        );
       
        //查看数据是否存在
        $return=M('device_type')->where("code='".$in['code']."'")->field("code")->find();
        //	echo M('device_type')->sql();
        if(!empty($return)){
            $rel=array(
                'code'=>'1',
                'msg'=>'数据已存在,请修改或新增其它类型!',
            );
        }else{
            $re_add=M('device_type')->data($arr)->add();
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
       // dump($rel);exit;
        $this->ajaxReturn($rel,'JSON');
    }

    //自助机类型删除 1
    public function delDevType(){
        //测试数据
        //$_POST['id'] = '1';

        $rules = array(
            array('id',1),
        );
        $in = $this->validParam($rules);//入参处理
        $id=$in['id'];
        if($id!='all'){
            $id=explode(',',$id);
        }
        if($id=='all'){
            $return=M("device_type")->where("1")->delete(); 
        }else{
            //删除多条
            foreach($id as $k=>$v){
                $return=M("device_type")->where("id='".$v."'")->delete(); // 根据条件更新记录
            }
        } 
        if($return>0){
            $rel=array(
                'code'=>'0',
                'msg'=>'数据删除成功!',
            );
        }else{
            $rel=array(
                'code'=>'1',
                'msg'=>'数据已删除!',
            );
        }
        //dump($rel);exit;
        $this->ajaxReturn($rel,'JSON');
    }

    //自助机类型修改 1
	public function updDevType(){
        //测试数据
		// $_POST['id']='1';
        // $_POST['code'] = '2';
        // $_POST['name'] = '32寸11111';
        // $_POST['system_version'] = '32windows';
        // $_POST['description'] = 'dasd';
        // $_POST['remark'] = ' ';
        // $_POST['modified_time'] = '自助挂号';

         //1是必传参数
        $rules = array(
            array('id',1),
            array('code',1),//类型编号
            array('name',1),//类型名称
            array('system_version',1),//系统版本
            array('description',0),//描述（描述类型详细配置等信息）
            array('remark',0),//备注
        );
        $in = $this->validParam($rules);//入参处理
        $modified_time= date("Y-m-d H:i:s");//更新时间
        $arr=array(
            'code'=>$in['code'],
            'name'=>$in['name'],
            'system_version'=>$in['system_version'],
            'description'=>$in['description'],
            'remark'=>$in['remark'],
            'modified_time'=>$modified_time,
        );
        $return=M("device_type")->where("id='".$in['id']."'")->save($arr); // 根据条件更新记录
        if($return>0){
            $rel=array(
                'code'=>'0',
                'msg'=>'数据修改成功!',
            );
        }else{
            $rel=array(
                'code'=>'1',
                'msg'=>'数据修改失败!',
            );
        }
		//dump($rel);exit;
		
		$this->ajaxReturn($rel,'JSON');
    }	
  
    //自助机类型查询 111111111
	public function selDevType(){
         //1是必传参数
        $rules = array( 
            array('code',0),//编号
            array('name',0),//名称
            array('id',0),
            array('per_page',0,'/^\d+$/'),//第几页
            
        );
        $in = $this->validParam($rules);//入参处理
        $where = '1';
		if(!empty($in['code'])){
		  $where.=" and code='".$in['code']."' ";
		}
		if(!is_null($in['name'])){
		  $where.=" and name='".$in['name']."'";
		}
        $id = $in['id'];
        $M = M('device_type');
        //每页显示多少条
        $page_num = M('config')->field("conf_value")->where("conf_key='per.page.num'")->select();
        $page_num = $page_num[0]['conf_value'];
        $count_rel = $M->where($where)->select();
        // 查询总记录数
        $count = intval(count($count_rel));
        //共多少页
        $pages = ceil($count/$page_num);
        //开始分页
        $in['per_page'] = $in['per_page'] - 1;
        $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];
        $offset = $in['per_page'] * $page_num;//偏移量

        $return_list ['code'] = '0';
        $return_list['msg'] = '查询成功';

		if(empty($id)){
            $rel = $M->where($where)->limit("'".$offset,$page_num."'")->select();
            $return_list['data']['page']['total_row_num'] = $count;//总条数
            $return_list['data']['page']['total_page_num'] = $pages;//总页数
            $return_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
            $return_list['data']['page']['page_row_num'] = $page_num;//每页多少条
		}else{
			$rel = $M->where("id='".$id."'")->select();
        }
    
        $return_list['data']['list']=$rel;
	
		$this->ajaxReturn($return_list,'JSON');
    }

   

    /**
     * @desc 自助机位置查询     
     * @param
     * @author 鲁翠霞
     * @final 2020-05-09
     */
    public function selDevPosition(){
     
         $rules = array(
            array('code',0),
            array('name',0),
            array('per_page',0,'/^\d+$/'),//第几页
        ); 
        $in = $this->validParam($rules);//入参处理
        $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
        $Md = M("device_position");
        $where = "1";
        if(!empty($in['code'])){
            $where .=" and code ='".$in['code']."'";
        }
        if(!empty($in['name'])){
            $where .=" and name='".$in['name']."'";
        }
        //$where = ltrim($where," and");

        $pre = C('DB_PREFIX');//表前缀
        $M = M();
        //每页显示多少条
        $page_num = $M->query("select conf_value from {$pre}config where conf_key='per.page.num'");
        $page_num = $page_num[0]['conf_value'];

        //查询总记录数
        $count =  $Md->field('id,name,code,describe,remark,create_time,modified_time')->where($where)->select();
        $count = count($count);
        //共多少页
        $pages = ceil($count/$page_num);
        //开始分页
        $in['per_page'] = $in['per_page'] - 1;
        $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];
  
        $offset = $in['per_page'] * $page_num;//偏移量

        $rel = $Md->field('id,name,code,describe,remark,create_time,modified_time')->where($where)->limit("'".$offset,$page_num."'")->select();
     
        $rel_list['code']='0';
        $rel_list['msg']='查询成功';
      
        $rel_list['data']['page']['total_row_num'] = $count;//总条数
        $rel_list['data']['page']['total_page_num'] = $pages;//总页数
        $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
        $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条

      
        
        $rel_list['data']['list'] = $rel;
        //dump($rel);exit;
        $this->ajaxReturn($rel_list,'JSON');
    }


    /**
     * @desc 自助机位置添加   
     * @param
     * @author 鲁翠霞
     * @final 2020-05-09
     */
    public function addDevPosition(){   
        //1是必传参数
        $rules = array(
            array('code',1),//区域id
            array('name',1),//区域名
            array('describe',0),//描述
            array('remark',0),//备注
            
        );
        $in = $this->validParam($rules);//入参处理

        $arr=array(
            'code'=>$in['code'],
            'name'=>$in['name'],
            'describe'=>$in['describe'],
            'remark'=>$in['remark'],
        );  
        //查看数据是否存在
        $return=M('device_position')->where("code='".$in['code']."'")->field("code")->find();
        if(!empty($return)){
            $rel=array(
                'code'=>'1',
                'msg'=>'数据已存在,请修改或新增其它类型!',
            );
        }else{
            $re_add=M('device_position')->data($arr)->add();   
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
        $this->ajaxReturn($rel,'JSON');
    }

        /**
     * @desc 修改自助机位置   
     * @param
     * @author 鲁翠霞
     * @final 2020-05-09
     */
    public function updDevPosition(){ 
        //1是必传参数
       $rules = array(
            array('id',1),//id
            array('code',1),//区域id
            array('name',1),//区域名
            array('describe',0),//描述
            array('remark',0),//备注
            
        );
        $in = $this->validParam($rules);//入参处理
        $arr=array(
            'code'=>$in['code'],
            'name'=>$in['name'],
            'describe'=>$in['describe'],
            'remark'=>$in['remark'],
        );  
        $M = M('device_position');   
        $return = $M->where("id='".$in['id']."'")->save($arr); // 根据条件更新记录
        $rel = array(
            'code'=>'0',
            'msg'=>'数据修改成功!',
        );
        $this->ajaxReturn($rel,'JSON');
    }   

     /**
     * @desc 删除自助机位置   
     * @param
     * @author 鲁翠霞
     * @final 2020-05-09
     */
    public function delDevPosition(){
       //1是必传参数
        $rules = array(
            array('id',1),
        );
        $in = $this->validParam($rules);//入参处理
        $id=$in['id'];
        if($id!='all'){
            $id=explode(',',$id);
            foreach($id as $k=>$v){
                $return=M("device_position")->where("id='".$v."'")->delete(); // 根据条件更新记录
            }
        }else{
            $return=M("device_position")->where("1")->delete();
        }
        if($return>0){
            $rel=array(
                'code'=>'0',
                'msg'=>'数据删除成功!',
            );
        }else{
            $rel=array(
                'code'=>'1',
                'msg'=>'数据已删除!',
            );
        }
       
        //dump($rel);exit;
        $this->ajaxReturn($rel,'JSON');
    }

    

    
    //生成自助机编号 1
    public function genNumber(){
        $sql='select code from ss_device order by id DESC limit 1';
        $return_list = M()->query($sql); 
        $newlist=substr($return_list[0]['code'],3)+1;
        $newcode='zzj'.$newlist;
        $rel['code']='0';
        $rel['msg']='';
        $rel['data']['new_code']=$newcode;
        //dump($rel);exit;
        $this->ajaxReturn($rel,'JSON');
         
    }
     //自助机设备添加 1
    public function addDevice(){   
        //1是必传参数
        $rules = array(
            array('code',1),//自助机编号
            array('position',1),//位置编号
            array('type_id',1),//自助机型号（型号表id）
            array('state',1,'/^([0-9])$/'),//自助机状态（1-启动，默认值 2-未启动 3-关机 4-不可用 9-已下线表示删除）
            array('use_property',1,'/^([0-9])$/'),//使用属性 1-采购 2-租用
            array('online_time',1),//上线时间
            array('heartbeat_time',1),//心跳时间
            array('rental_price',0),//租用价格(年分)
            array('rental_time',1),//租用时间
            array('expired_time',1),//维护有效期
            array('remark',0),//备注
            
        );
        $in = $this->validParam($rules);//入参处理
        $create_time= date("Y-m-d H:i:s");//创建时间
        $modified_time= date("Y-m-d H:i:s");//更新时间
        
        $arr=array(
            'code'=>$in['code'],
            'position_id'=>$in['position'],
            'type_id'=>$in['type_id'],
            'state'=>$in['state'],
            'use_property'=>$in['use_property'],
            'online_time'=>$in['online_time'],
            'heartbeat_time'=>$in['heartbeat_time'],
            'rental_price'=>$in['rental_price'],
            'rental_time'=>$in['rental_time'],
            'expired_time'=>$in['expired_time'],
            'remark'=>$in['remark'],
            'create_time'=>$create_time,
            'modified_time'=>$modified_time,
        );  
        //查看数据是否存在
        $return=M('device')->where("code='".$in['code']."'")->field("code")->find();
        if(!empty($return)){
            $rel=array(
                'code'=>'1',
                'msg'=>'数据已存在,请修改或新增其它类型!',
            );
        }else{
            $re_add=M('device')->data($arr)->add();   
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
        $this->ajaxReturn($rel,'JSON');
    }
    
    //自助机设备删除 1
    public function delDevice(){
        //测试数据
       // $_POST['id'] = '3';
       //1是必传参数
        $rules = array(
            array('id',1),
        );
        $in = $this->validParam($rules);//入参处理
        $id=$in['id'];
        if($id!='all'){
            $id=explode(',',$id);
            foreach($id as $k=>$v){
                $return=M("device")->where("id='".$v."'")->delete(); // 根据条件更新记录
            }
        }else{
            $return=M("device")->where("1")->delete();
        }
        if($return>0){
            $rel=array(
                'code'=>'0',
                'msg'=>'数据删除成功!',
            );
        }else{
            $rel=array(
                'code'=>'1',
                'msg'=>'数据已删除!',
            );
        }
       
        //dump($rel);exit;
        $this->ajaxReturn($rel,'JSON');
    }
    
    //自助机设备修改 1
	public function updDevice(){
		
        //测试数据
        // $_POST['id']='5';
        // $_POST['code'] = '111111';
        // $_POST['position'] = '3';
        // $_POST['type_id'] = '1';
        // $_POST['state'] = '1';
        // $_POST['use_property'] = '1';
        // $_POST['online_time'] = date("Y-m-d H:i:s");
        // $_POST['heartbeat_time'] =  date("Y-m-d H:i:s");
        // $_POST['rental_price'] = '111111';
        // $_POST['rental_time'] =  date("Y-m-d H:i:s");
        // $_POST['expired_time'] =  date("Y-m-d H:i:s");
        // $_POST['remark'] = '11';
        // $_POST['modified_time'] = '';
        
        //1是必传参数
        $rules = array(
            array('id',1),//自助机编号
            array('code',1),//自助机编号
            array('position',1),//位置编号
            array('type_id',1),//自助机型号（型号表id）
            array('state',1,'/^([0-9])$/'),//自助机状态（1-启动，默认值 2-未启动 3-关机 4-不可用 9-已下线表示删除）
            array('use_property',1,'/^([0-9])$/'),//使用属性 1-采购 2-租用
            array('online_time',1),//上线时间
            array('heartbeat_time',1),//心跳时间
            array('rental_price',0),//租用价格(年分)
            array('rental_time',1),//租用时间
            array('expired_time',1),//维护有效期
            array('remark',0),//备注
            
        );
        $in = $this->validParam($rules);//入参处理
        $create_time= date("Y-m-d H:i:s");//创建时间
        $modified_time= date("Y-m-d H:i:s");//更新时间
      
        $arr=array(
            'code'=>$in['code'],
            'position_id'=>$in['position'],
            'type_id'=>$in['type_id'],
            'state'=>$in['state'],
            'use_property'=>$in['use_property'],
            'online_time'=>$in['online_time'],
            'heartbeat_time'=>$in['heartbeat_time'],
            'rental_price'=>$in['rental_price'],
            'rental_time'=>$in['rental_time'],
            'expired_time'=>$in['expired_time'],
            'remark'=>$in['remark'],
            'modified_time'=>$modified_time,
        );
				
        $return=M("device")->where("id='".$in['id']."'")->save($arr); // 根据条件更新记录
        if($return>0){
            $rel=array(
                'code'=>'0',
                'msg'=>'数据修改成功!',
            );
        }else{
            $rel=array(
                'code'=>'1',
                'msg'=>'数据修改失败!',
            );
        }
		//dump($rel);exit;
		$this->ajaxReturn($rel,'JSON');
    }

    /**
     * @desc 自助机设备查询
     * @param
     * @author 鲁翠霞
     * @final 2020-04-20
     */
    public function selDevice(){
        //测试数据
       // $_POST['code'] = '';

        //1是必传参数
        $rules = array(
            array('code',0),//自助机编号
            array('state',0),//自助机状态 1：启动 2：未启动 3:关机 4:不可用 9:已下线
            array('position_id',0),//自助机位置
            array('per_page',0,'/^\d+$/'),//第几页
        ); 
        $in = $this->validParam($rules);//入参处理
        $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
       
        $where = '1';
        if(!empty($in['code'])){
          $where .=" and t1.code='".$in['code']."'";
        }
        if(!empty($in['state'])){
          $where .=" and t1.state='".$in['state']."'";
        }
        if(!empty($in['position_id'])){
          $where .=" and t1.position_id='".$in['position_id']."'";
        }
        $where = ltrim($where," and");
         //组织sql
        $pre = C('DB_PREFIX');//表前缀
        $M = M();
        //每页显示多少条
        $page_num = $M->query("select conf_value from {$pre}config where conf_key='per.page.num'");
        $page_num = $page_num[0]['conf_value'];
  
        //查询总记录数
        $count = $M->query("select count(*) as tp_cnt from `{$pre}device` as t1 left join `{$pre}device_type` as t2 on t1.type_id=t2.id left join `{$pre}device_position` as t3 on t1.position_id=t3.id where {$where}");
        $count = intval($count[0]['tp_cnt']);

        //共多少页
        $pages = ceil($count/$page_num);
        //开始分页
        $in['per_page'] = $in['per_page'] - 1;
        $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];
  
        $offset = $in['per_page'] * $page_num;//偏移量

        $field = "t1.id,t1.code,t1.position_id,t1.state,t1.use_property,t1.type_id,t1.online_time,t1.heartbeat_time,t1.rental_price,t1.rental_time,t1.expired_time,t1.remark,t2.name as type_name,t3.name as position_name";
        $sql = "select {$field} from `{$pre}device` as t1 left join `{$pre}device_type` as t2 on t1.type_id=t2.id left join `{$pre}device_position` as t3 on t1.position_id=t3.id where {$where} limit {$offset},{$page_num}";
        $rel = $M->query($sql);
        //dump($rel);exit;

       
        $rel_list['code']='0';
        $rel_list['msg']='查询成功';
        
        $rel_list['data']['page']['total_row_num'] = $count;//总条数
        $rel_list['data']['page']['total_page_num'] = $pages;//总页数
        $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
        $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条
        
        foreach($rel as $k=>$v){
            //自助机状态 1：启动 2：未启动 3:关机 4:不可用 9:已下线
            if($v['state']==1){
                $rel[$k]['state_name']='启动';
            }elseif($v['state']==2){
                $rel[$k]['state_name']='未启动';
            }elseif($v['state']==3){
                $rel[$k]['state_name']='关机';
            }elseif($v['state']==4){
                $rel[$k]['state_name']='不可用';
            }elseif($v['state']==9){
                $rel[$k]['state_name']='已下线';
            }
            //使用属性 1:采购 2:租用
           if($v['use_property']==1){
                $rel[$k]['use_property_name']='采购';
           }elseif($v['use_property']==2){
                $rel[$k]['use_property_name']='租用';
           }
        }
        $rel_list['data']['list'] = $rel;
        $this->ajaxReturn($rel_list,'JSON');
    }


    
    /**
     * @desc 假日管理添加
     * @param
     * @author 鲁翠霞
     * @final 2020-04-19
     */
    public function addHoliday(){
        $rules = array(
            array('title',1),//名称（假日名或是特殊日期名）
            array('start_date',1, '/^\d{4}-\d{2}-\d{2}$/'),
            array('end_date',1, '/^\d{4}-\d{2}-\d{2}$/'), 
            array('description',0) //描述
        );
        $in = $this->validParam($rules);//入参处理
        $arr=array(
            'title'=>$in['title'],
            'start_date'=>$in['start_date'],
            'end_date'=>$in['end_date'],
            'description'=>$in['description'],
        );          
        $M = M('holiday');
        // 获取指定日期区间的所有日期
        $date_arr = $this->periodDate($in['start_date'],$in['end_date']);
        $num = 0;
        foreach($date_arr as $k=>$v){
            $return_date = $M->where("'".$v."' between start_date and end_date")->field('title')->select();
            if(count($return_date)>0){
                $num++;
            }
        }
        if($num>0){
            $rel=array(
                'code'=>'1',
                'msg'=>'当前日期区间的日期已添加过!', 
            );
        }else{
            $re_add=M('holiday')->data($arr)->add();
            $rel=array(
                'code'=>'0',
                'msg'=>'数据添加成功!',
            );
        }
        //dump($rel);exit;
        $this->ajaxReturn($rel,'JSON');
        
    }


     /**
     * @desc 假日管理删除
     * @param
     * @author 鲁翠霞
     * @final 2020-04-19
     */
    public function delHoliday(){
        //1是必传参数
		$rules = array(
            array('id_list',1),
		);
        $in = $this->validParam($rules);//入参处理
        $M = M("holiday");
		foreach($in['id_list'] as $k=>$v){
			if($v['id']=='all'){
				$return = $M->where('1')->delete(); 
			}else{
				$return = $M->where("id='".$v['id']."'")->delete(); // 根据条件更新记录
			}
		}
		$rel=array(
			'code'=>'0',
			'msg'=>'数据删除成功!',
		);
		
        $this->ajaxReturn($rel,'JSON');
    }

    /**
     * @desc 假日管理修改
     * @param
     * @author 鲁翠霞
     * @final 2020-04-19
     */
	public function updHoliday(){
        $rules = array(
            array('id','1'),
            array('title',1),//名称（假日名或是特殊日期名）
            array('start_date',1, '/^\d{4}-\d{2}-\d{2}$/'),
            array('end_date',1, '/^\d{4}-\d{2}-\d{2}$/'), 
            array('description',0) //描述
        );
        $in = $this->validParam($rules);//入参处理
        $arr=array(
            'title'=>$in['title'],
            'start_date'=>$in['start_date'],
            'end_date'=>$in['end_date'],
            'description'=>$in['description'],
        );          
        $M = M('holiday');	
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
     * @desc 假日管理查询
     * @param
     * @author 鲁翠霞
     * @final 2020-04-19
     */
    public function selHoliday(){
        //1是必传参数
		$rules = array(
            array('id',0),
            array('per_page',0,'/^\d+$/'),//第几页
            array('title',0),
		);
        $in = $this->validParam($rules);//入参处理
        if(!empty($in['title'])){
            $where ="  title='".$in['title']."' ";
          }

        $M = M('holiday');	
        $field = 'id,title,start_date,end_date,description';
        //每页显示多少条
        $page_num = M('config')->field("conf_value")->where("conf_key='per.page.num'")->select();
        $page_num = $page_num[0]['conf_value'];
        $count_rel = $M->where($where)->select();
        // 查询总记录数
        $count = intval(count($count_rel));
        //共多少页
        $pages = ceil($count/$page_num);
        //开始分页
        $in['per_page'] = $in['per_page'] - 1;
        $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];
        $offset = $in['per_page'] * $page_num;//偏移量

        $rel_list['code']='0';
        $rel_list['msg']='查询成功';
		if(empty($in['id'])){     
            $rel = $M->field($field)->where($where)->limit($offset,$page_num)->select();
      
            $rel_list['data']['page']['total_row_num'] = $count;//总条数
            $rel_list['data']['page']['total_page_num'] = $pages;//总页数
            $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
            $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条
       
		}else{
			$rel = $M->where("id='".$in['id']."'")->field($field)->select();
		}
        $rel_list['data']['list']=$rel;
        $this->ajaxReturn($rel_list,'JSON');
        
    }


 
    /**
     * @desc 指令表查询
     * @param
     * @author 鲁翠霞
     * @final 2020-04-16
     */
    public function selCommand(){
        $rel = M('command')->field('id,name,content')->select();
        $rel_list ['code'] = '0';
        $rel_list['msg'] = '查询成功';
        $rel_list['data'] = $rel;
        $this->ajaxReturn($rel_list,'JSON');
    }

      /**
     * @desc 指令下发
     * @param
     * @author 鲁翠霞
     * @final 2020-04-16
     */
    public function issuedInstructions(){
         //1是必传参数
        $rules = array( 
            array('dev',1),//自助机编号
            array('command',1)//指令集合 
        );
        $in = $this->validParam($rules);//入参处理
        session_start(); //开启Session功能
        //判断是所有机器还是个别机器 
        if(count($in['dev'])==1 && $in['dev'][0]['code']=='all'){
            foreach($in['command'] as $k=>$v){  
                $arr = array(
                    'dev_code'=>$in['dev'],//自助机编号
                    'command_id'=>$v['id'],//指令id
                    'command_name'=>$v['name'],//指令名 
                    'seq'=>$v['seq'],//执行顺序 
                    'operator_user_id'=>$_SESSION['uid'],//操作员id
                    'operator'=>$_SESSION['uname'],//操作员名
                );
                $re_add = M('command_log')->data($arr)->add();
            }
        }elseif(count($in['dev'])>1){
            foreach($in['dev'] as $k=>$v){    
                foreach($in['command'] as $key=>$val){
                    $arr = array(
                        'dev_code'=>$v['code'],//自助机编号
                        'command_id'=>$val['id'],//指令id
                        'command_name'=>$val['name'],//指令名 
                        'seq'=>$val['seq'],//执行顺序 
                        'operator_user_id'=>$_SESSION['uid'],//操作员id
                        'operator'=>$_SESSION['uname'],//操作员名
                    );
                    $re_add = M('command_log')->data($arr)->add();
                }
               
            }
        }
        $rel=array(
            'code'=>'0',
            'msg'=>'指令下发成功!',
        );
        $this->ajaxReturn($rel,'JSON');
    }


    /**
     * @desc 指令下发日志表查询
     * @param
     * @author 鲁翠霞
     * @final 2020-05-11
     */
    public function selCommandLog(){
        $rules = array(
            array('dev_code',0),//机器编号
        );
        $in = $this->validParam($rules);//入参处理
        $pre = C('DB_PREFIX');//表前缀
        $M = M();
        //查最近多少秒的数据 
        $heartbeat = $M->query("select conf_value from {$pre}config where conf_key='heartbeat.interval'");
        //查询最近10s的数据
        $rel = M('command_log')->field(" dev_code, operation_time,command_name ")->where(" operation_time > DATE_SUB(NOW(),INTERVAL  '".$heartbeat[0]['conf_value']."' SECOND)")->order('operation_time desc')->select();
       
        $rel_list['code'] = 0; 
        $rel_list['msg'] = "查询成功";
        $rel_list['data']['devCode'] = $in['dev_code'];
        $rel_list['data']['commandList'] = $rel;
    

        $this->ajaxReturn($rel_list,'JSON');
    }



    /**
     * @desc 文案配置添加
     * @param
     * @author 鲁翠霞
     * @final 2020-04-23
     */
    public function addCopyConf(){
        $rules = array(
            array('title',1),//名称
            array('type',1),//文案类型 
            array('content',1), //文案内容
            array('remark',0) //备注
        );
        $in = $this->validParam($rules);//入参处理
        $arr=array(
            'title'=>$in['title'],
            'type'=>$in['type'],
            'content'=>$in['content'],
            'remark'=>$in['remark'],
        );          
        $M = M('copy_writer');
        $return = $M->where("title = '".$in['title']."'")->field('title')->select();
  
        if(!empty($return)){
            $rel=array(
                'code'=>'1',
                'msg'=>'当前文案已存在!', 
            );
        }else{
            $re_add = $M->data($arr)->add();
            $rel = array(
                'code'=>'0',
                'msg'=>'文案添加成功!',
            );
        }
     
        $this->ajaxReturn($rel,'JSON');
        
    }

    /**
     * @desc 文案删除
     * @param
     * @author 鲁翠霞
     * @final 2020-04-23
     */
    public function delCopyConf(){
        //1是必传参数
		$rules = array(
            array('id_list',1),
		);
        $in = $this->validParam($rules);//入参处理
        $M = M("copy_writer");
		foreach($in['id_list'] as $k=>$v){
			if($v['id']=='all'){
				$return = $M->where('1')->delete(); 
			}else{
				$return = $M->where("id='".$v['id']."'")->delete(); // 根据条件更新记录
			}
		}
		$rel=array(
			'code'=>'0',
			'msg'=>'数据删除成功!',
		);
		
        $this->ajaxReturn($rel,'JSON');
    }

    /**
     * @desc 文案修改
     * @param
     * @author 鲁翠霞
     * @final 2020-04-23
     */
    public function updCopyConf(){
        //1是必传参数
        $rules = array(
            array('id',1),//名称
            array('title',1),//名称
            array('type',1),//文案类型 
            array('content',1), //文案内容
            array('remark',0) //备注
        );
        $in = $this->validParam($rules);//入参处理
        $arr = array(
            'title'=>$in['title'],
            'type'=>$in['type'],
            'content'=>$in['content'],
            'remark'=>$in['remark'],
        );    
        $M = M("copy_writer");
				
        $return = $M->where("id='".$in['id']."'")->save($arr); // 根据条件更新记录        
        $rel=array(
            'code'=>'0',
            'msg'=>'数据修改成功!',
        );
       
		$this->ajaxReturn($rel,'JSON');
    }


    /**
     * @desc 文案查询
     * @param
     * @author 鲁翠霞
     * @final 2020-04-23
     */
    public function selCopyConf(){
        $rules = array(
            array('id',0),//名称
            array('title',0),//名称
            array('per_page',0,'/^\d+$/'),//第几页
        );
        $in = $this->validParam($rules);//入参处理
        $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
        $where = '';
        if(!empty($in['id'])){
          $where.=" and id='".$in['id']."' ";
        }
        if(!empty($in['title'])){
            $where.=" and title='".$in['title']."' ";
          }
        $where = ltrim($where," and");

         //每页显示多少条
         $page_num = M('config')->field("conf_value")->where("conf_key='per.page.num'")->select();
         $page_num = $page_num[0]['conf_value'];
         $count_rel = M('copy_writer')->select();
         // 查询总记录数
         $count = intval(count($count_rel));
         //共多少页
         $pages = ceil($count/$page_num);
         //开始分页
         $in['per_page'] = $in['per_page'] - 1;
         $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];
         $offset = $in['per_page'] * $page_num;//偏移量

         $rel_list ['code'] = '0';
         $rel_list['msg'] = '查询成功';
        if(empty($where)){
            $rel = M('copy_writer')->limit("'".$offset,$page_num."'")->select();
    
            $rel_list['data']['page']['total_row_num'] = $count;//总条数
            $rel_list['data']['page']['total_page_num'] = $pages;//总页数
            $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
            $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条
        }else{
            $rel = M('copy_writer')->where($where)->select();
        }
    
        $rel_list['data']['list']=$rel;
       
        $this->ajaxReturn($rel_list,'JSON');
    }
    

    /**
     * @desc 接口配置添加
     * @param
     * @author 鲁翠霞
     * @final 2020-04-23
     */ 
    public function addInterface(){
        $rules = array(
            array('interface_code',1),//接口编号
            array('interface_url',1),//接口地址
            array('description',1), //描述 
            array('remark',0) //备注
        );
        $in = $this->validParam($rules);//入参处理
        $arr=array(
            'interface_code'=>$in['interface_code'],
            'interface_url'=>$in['interface_url'],
            'description'=>$in['description'],
            'remark'=>$in['remark'],
        );          
        $M = M('interface_configuration');
        $return = $M->where("interface_code = '".$in['interface_code']."'")->field('interface_code')->select();

        if(!empty($return)){
            $rel=array(
                'code'=>'1',
                'msg'=>'当前接口已存在!', 
            );
        }else{
            $re_add = $M->data($arr)->add();
            $rel = array(
                'code'=>'0',
                'msg'=>'接口添加成功!',
            );
        }
     
        $this->ajaxReturn($rel,'JSON');
        
    }


    /**
     * @desc 接口配置删除
     * @param
     * @author 鲁翠霞
     * @final 2020-04-23
     */
    public function delInterface(){
        //1是必传参数
		$rules = array(
            array('id_list',1),
		);
        $in = $this->validParam($rules);//入参处理
        $M = M("interface_configuration");
		foreach($in['id_list'] as $k=>$v){
			if($v['id']=='all'){
				$return = $M->where('1')->delete(); 
			}else{
				$return = $M->where("id='".$v['id']."'")->delete(); // 根据条件更新记录
			}
		}
		$rel=array(
			'code'=>'0',
			'msg'=>'数据删除成功!',
		);
		
        $this->ajaxReturn($rel,'JSON');
    }

      /**
     * @desc 接口配置修改
     * @param
     * @author 鲁翠霞
     * @final 2020-04-23
     */
    public function updInterface(){
        //1是必传参数
        $rules = array(
            array('id',1),//名称
            array('interface_code',1),//接口编号
            array('interface_url',1),//接口地址
            array('description',1), //描述 
            array('remark',0) //备注
        );
        $in = $this->validParam($rules);//入参处理
        $arr=array(
            'interface_code'=>$in['interface_code'],
            'interface_url'=>$in['interface_url'],
            'description'=>$in['description'],
            'remark'=>$in['remark'],
        );          
        $M = M('interface_configuration');
				
        $return = $M->where("id='".$in['id']."'")->save($arr); // 根据条件更新记录        
        $rel=array(
            'code'=>'0',
            'msg'=>'数据修改成功!',
        );
       
		$this->ajaxReturn($rel,'JSON');
    }


    
    /**
     * @desc 接口配置查询
     * @param
     * @author 鲁翠霞
     * @final 2020-04-23
     */
    public function selInterface(){
       
        $rules = array(
            array('interface_code',0),//名称
            array('per_page',0,'/^\d+$/'),//第几页
        );
        $in = $this->validParam($rules);//入参处理
        $in['per_page'] = empty($in['per_page']) ? 1 : $in['per_page'];//默认第一页
        $where = '';
        if(!empty($in['interface_code'])){
          $where.=" interface_code='".$in['interface_code']."' ";
        }

        //每页显示多少条
        $page_num = M('config')->field("conf_value")->where("conf_key='per.page.num'")->select();
        $page_num = $page_num[0]['conf_value'];
        $count_rel = M('interface_configuration')->select();
        // 查询总记录数
        $count = intval(count($count_rel));
        //共多少页
        $pages = ceil($count/$page_num);
        //开始分页
        $in['per_page'] = $in['per_page'] - 1;
        $in['per_page'] = $in['per_page'] < 0 ? 0 : $in['per_page'];
        $offset = $in['per_page'] * $page_num;//偏移量

        $rel_list ['code'] = '0';
        $rel_list['msg'] = '查询成功';
        if(empty($where)){
            $rel = M('interface_configuration')->limit("'".$offset,$page_num."'")->select();
            
            $rel_list['data']['page']['total_row_num'] = $count;//总条数
            $rel_list['data']['page']['total_page_num'] = $pages;//总页数
            $rel_list['data']['page']['per_page'] = $in['per_page'] + 1;//当前页
            $rel_list['data']['page']['page_row_num'] = $page_num;//每页多少条
       
        }else{
            $rel = M('interface_configuration')->where($where)->select();
        }
        
        $rel_list['data']['list']=$rel;
        
        $this->ajaxReturn($rel_list,'JSON');
    }
    


}