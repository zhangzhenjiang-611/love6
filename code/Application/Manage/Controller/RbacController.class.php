<?php
namespace Manage\Controller;
use Think\Controller;
class RbacController extends Controller {
    public function addrole(){
		$this->display();
    }
  
	
	/**
     * @desc 添加角色表单处理
     * @param
     * @author 鲁翠霞
     * @final 2020-04-22
     */
	public function addRoleHandle(){	
		$rules = array(
            array('name',1),//角色名称
            array('status',1),//开启状态
            array('remark',0),//角色描述
        );
		$in = $this->validParam($rules);//入参处理
		$in['pid'] = '0';//trim(I('post.pid'));//暂时用不上，设置为0
		$arr=array(
			 'name'=>$in['name'],
			 'pid'=>$in['pid'],
			 'status'=>$in['status'],
			 'remark'=>$in['remark'],
		);
		//查看数据是否存在
		$return=M('role')->where("name='".$in['name']."'")->field("name")->find();
		if(!empty($return)){
			$rel=array(
				'code'=>'1',
				'msg'=>'数据已存在,请修改或新增其它类型!',
			);
		}else{
			$re_add=M('role')->data($arr)->add();
			if($re_add){
				$rel=array( 
					'code'=>'0',
					'msg'=>'添加成功!',
				);
			}else{
				$rel=array(
					'code'=>'1',
					'msg'=>'添加失败!',
				);
			}
		}
	    $this->ajaxReturn($rel,'JSON');
		 
	}


	/** 
     * @desc 修改角色表单处理
     * @param
     * @author 鲁翠霞
     * @final 2020-04-22
     */
	public function updRoleHandle(){	
		$rules = array(
			array('id',1),//角色名称
			array('pid',1),//角色识别：0:超级管理员1:其它
            array('name',1),//角色名称
            array('status',1),//开启状态
            array('remark',0),//角色描述
        );
		$in = $this->validParam($rules);//入参处理
		$arr=array(
			 'name'=>$in['name'],
			 'pid'=>$in['pid'],
			 'status'=>$in['status'],
			 'remark'=>$in['remark'],
		);
	
		$return = M('role')->where("id='".$in['id']."'")->save($arr); // 根据条件更新记录
		if($return){
			$rel=array( 
				'code'=>'0',
				'msg'=>'修改成功!',
			);
		}else{
			$rel=array(
				'code'=>'1',
				'msg'=>'修改失败!',
			);
		}
		
	    $this->ajaxReturn($rel,'JSON');
		 
	}
	
	/**
     * @desc 角色管理（角色列表）
     * @param
     * @author 鲁翠霞
     * @final 2020-04-22
     */
	public function roleList(){
		$rules = array(
			array('name',0),//权限英文名
            array('status',0),//开启状态
        ); 
		$in = $this->validParam($rules);//入参处理
		$where = '1';
		if(!empty($in['name'])){
		  $where.=" and name='".$in['name']."' ";
		}
		if(!is_null($in['status'])){
		  $where.=" and status='".$in['status']."'";
		}
		$return = M('role')->where($where)->select();
		//dump( M('role')->_sql());
		$rel['code'] = '0';
		$rel['msg'] = '查询成功';
		$rel['data'] = $return;
		$this->ajaxReturn($rel,'JSON');
	}


	/**
     * @desc 添加权限的下拉框（节点） 
     * @param
     * @author 鲁翠霞
     * @final 2020-04-22
     */
	public function addNode(){
		$return = M('node')->where('level!=3')->order('sort')->select();
		$rel['code'] = '0';
		$rel['msg'] = '查询成功';
		$rel['data'] = $return;
		$this->ajaxReturn($rel,'JSON');
	}

 
	/**
     * @desc 添加权限（节点） 表单处理 
     * @param
     * @author 鲁翠霞
     * @final 2020-04-22
     */
	public function addNodeHande(){
		$rules = array(
			array('name',1),//权限英文名
			array('menu_name',1),//菜单权限英文名
			array('title',1),//权限中文名
            array('status',1),//开启状态
			array('remark',0),//备注
			array('sort',1),//排序
			array('pid',1),//父标识
			array('level',1),//层次
			array('url',1),//url
			array('project_address',1),//项目地址
			array('config_info',1)//配置信息
        ); 
		$in = $this->validParam($rules);//入参处理
		
		$node = M("node"); // 实例化User对象
		$arr = array(
			'name'=>$in['name'],
			'menu_name'=>$in['menu_name'],
			'title'=>$in['title'],
			'pid'=>$in['pid'],
			'status'=>$in['status'],
			'remark'=>$in['remark'],
			'sort'=>$in['sort'],
			'level'=>$in['level'],
			'url'=>$in['url'],
			'project_address'=>$in['project_address'],
			'config_info'=>$in['config_info']
	   );
	   //查看数据是否存在
	   $return = $node->where("name='".$in['name']."'")->field("name")->find();
	   if(!empty($return)){
		   $rel=array(
			   'code'=>'1',
			   'msg'=>'数据已存在,请修改或新增其它类型!',
		   );
	   }else{
		   $re_add = $node->data($arr)->add();
		   if($re_add){
			   $rel = array( 
				   'code'=>'0',
				   'msg'=>'添加成功!',
			   );
		   }else{
			   $rel = array(
				   'code'=>'1',
				   'msg'=>'添加失败!',
			   );
		   }
	   }
	   $this->ajaxReturn($rel,'JSON');

	}


	/**
     * @desc 修改权限（节点） 表单处理 
     * @param
     * @author 鲁翠霞
     * @final 2020-04-22
     */
	public function updNodeHande(){
		$rules = array(
			array('id',1),//权限英文名
			array('name',1),//权限英文名
			array('menu_name',1),//菜单权限英文名
			array('title',1),//权限中文名
            array('status',1),//开启状态
			array('remark',0),//备注
			array('sort',1),//排序
			array('pid',1),//父标识
			array('level',1),//层次
			array('url',1),//url
			array('project_address',1),//项目地址
			array('config_info',1)//配置信息
        ); 
		$in = $this->validParam($rules);//入参处理
		
		$node = M("node"); // 实例化User对象
		$arr = array(
			'name'=>$in['name'],
			'menu_name'=>$in['menu_name'],
			'title'=>$in['title'],
			'pid'=>$in['pid'],
			'status'=>$in['status'],
			'remark'=>$in['remark'],
			'sort'=>$in['sort'],
			'level'=>$in['level'],
			'url'=>$in['url'],
			'project_address'=>$in['project_address'],
			'config_info'=>$in['config_info']
	   );
	   $return = $node->where("id='".$in['id']."'")->save($arr); // 根据条件更新记录
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
	   $this->ajaxReturn($rel,'JSON');

	}

	/**
     * @desc 权限管理（节点列表）
     * @param
     * @author 鲁翠霞
     * @final 2020-04-22
     */
	public function nodeList(){
		$node = M("node")->order('sort')->select();
		$t = new\Org\Util\Tree();
		$return = $t::create($node);
		$rel['code'] = '0';
		$rel['msg'] = '查询成功';
		$rel['data'] = $return;
		$this->ajaxReturn($rel,'JSON');
	}


	 /**
     * @desc 删除权限
     * @param
     * @author 鲁翠霞
     * @final 2020-04-22
     */
	public function deletenode(){
		$rules = array(
			array('id',1),
        ); 
		$in = $this->validParam($rules);//入参处理
		$return = M('node')->where("id=".$in['id'])->delete();
		$rel['code'] = '0';
		$rel['msg'] = '删除成功'; 
		$this->ajaxReturn($rel,'JSON');
	}


	 /** 
     * @desc 添加用户时 用户组的下拉框里
     * @param
     * @author 鲁翠霞
     * @final 2020-04-22
     */
	public function addUser(){
		$return = M("role")->select();
		$rel['code'] = '0';
		$rel['msg'] = '查询成功';
		$rel['data'] = $return;
		$this->ajaxReturn($rel,'JSON');
		
	}

	 /** 
     * @desc 添加用户处理
     * @param
     * @author 鲁翠霞
     * @final 2020-04-22
     */
	public function addUserHande(){
		$rules = array(
			array('username',1),
			array('password',1),
			array('status',1),//开启状态
			array('role_id',1)
        ); 
		$in = $this->validParam($rules);//入参处理

		if($_SERVER["REMOTE_ADDR"]=='::1'){
			$ip = '127.0.0.1';
		}else{
			$ip = $_SERVER["REMOTE_ADDR"];
		}   
		$data['username']=$in['username'];
		$data['password']=md5($in['password']);
		$data['login_time']=date('Y-m-d H:i:s');
		$data['login_ip']=$ip;
		$data['status']=$in['status'];
		$Muser = M("user");
		//先判断是否存在该用户名
		$rel_user = $Muser->where("username='".$in['username']."'")->find();
		if(isset($rel_user)){
			$rel['code'] = '1';
			$rel['msg'] = '用户已存在';
		}else{ 
			$uid = $Muser->add($data);
			if($uid){
				//用户添加成功后添加用户角色表
				$role['role_id']=$in['role_id'];
				$role['user_id']=$uid;
				M("role_user")->add($role);
				$rel['code'] = '0';
				$rel['msg'] = '添加成功';
			}else{
				$rel['code'] = '1';
				$rel['msg'] = '添加失败';
			}
		}
		$this->ajaxReturn($rel,'JSON');
	}


	 /** 
     * @desc 修改用户处理
     * @param
     * @author 鲁翠霞
     * @final 2020-04-26
     */ 
	public function updUserHande(){
		$rules = array(
			array('uid',1),
			array('username',1),
			array('password',0),
			array('status',1),//开启状态
			array('role_id',1)//角色id
		); 
		$in = $this->validParam($rules);//入参处理
  
		if(empty($in['password'])){
			$arr = array(
				"username"=>$in['username'],
				"status"=>$in['status'],
			);
		}else{
			$arr = array(
				"username"=>$in['username'],
				"password"=>md5($in['password']),
				"status"=>$in['status'],
			);
		}
	
		$return=M("user")->where("id='".$in['uid']."'")->save($arr); // 根据条件更新记录

		if(!empty($in['role_id'])){
			//用户添加成功后添加用户角色表
			$arr2 = array(
				"role_id"=>$in['role_id'],
				"user_id"=>$in['uid']
			);
			$return=M("role_user")->where("user_id='".$in['uid']."'")->save($arr2);

			$rel['code'] = '0';
			$rel['msg'] = '修改成功';
		}else{
			$rel['code'] = '1'; 
			$rel['msg'] = '修改失败';
		}
		$this->ajaxReturn($rel,'JSON');
	}

	/** 
     * @desc 用户列表
     * @param
     * @author 鲁翠霞
     * @final 2020-04-22
     */
	public function userList(){
        $rules = array(
            array('name',0),
            array('administrator', 0),
        );
        $in = $this->validParam($rules);//入参处理
        $where = array();
        if(!empty($in['name'])){
            //$where.=" username ='".$in['name']."' ";
            $where['u.username'] = $in['name'];
        }
        if(!empty($in['administrator'])){
            //$where1.=" ss_role.id ='".$in['administrator']."'";
            $where['r.id'] = $in['administrator'];
        }
/*        $user = M('user')->field('password,create_time,modified_time',true)->where($where)->select();

        foreach ($user as &$v) {
            $arr = M('role')->join('ss_role_user on ss_role_user.role_id=ss_role.id')->field('ss_role.id,ss_role.name')->where($where1)->select();
            if (!empty($arr)) {
                $v['Role'] = $arr;
            }
        }
        print_r($user);
        exit;*/

        $user = M('user')->alias('u')
            ->join(' JOIN __ROLE_USER__ ru on u.id = ru.user_id')
            ->join(' JOIN __ROLE__ r on r.id = ru.role_id')
            ->field('u.id,u.username,u.login_time,u.login_ip,u.status,r.id rid,r.name rname')
            ->where($where)
            ->select();
      foreach ($user as &$v) {
          $v['Role'][] = [
              'id' => $v['rid'],
              'name' => $v['rname'],
          ];
          unset($v['rid']);
          unset($v['rname']);

      }
        $rel['code'] = '0';
        $rel['msg'] = '查询成功';
        $rel['data'] = $user;

        $this->ajaxReturn($rel,'JSON');
	
	}


	/** 
     * @desc 配置权限
     * @param
     * @author 鲁翠霞
     * @final 2020-04-22
     */
	public function access(){
		$rules = array(
			array('rid',1),//角色id
        );
		$in = $this->validParam($rules);//入参处理
        $in['rid'] = 4;
		$node = M("node")->order('sort')->select();
		$t = new \Org\Util\Tree();
		$node = $t::create($node);
		$data = array();//$data用于存放最新数组，里面包含当前用户组是否有每一个权限
		$role = array();
		$name = M("role")->getFieldById($in['rid'],'name');
		$role['rname'] = $name;
		$role['rid'] = $in['rid'];
		$access = M("access");

		foreach($node as $value){
			$count = $access->where('role_id='.$in['rid'].' and node_id='.$value['id'])->count();
			if($count){
				$value['access'] = 1;
			}else{
				$value['access'] = 0;
			}
			$data[]=$value;
		}

	
		$rel['code'] = '0';
		$rel['msg'] = '查询成功';
		$rel['data']['role'] = $role;
		$rel['data']['list'] = $data;
		
		$this->ajaxReturn($rel,'JSON');
	}


	/** 
     * @desc 添加角色权限表（节点表）
     * @param
     * @author 鲁翠霞
     * @final 2020-04-26
     */
	public function setAccess(){
		$rules = array(
			array('rid',1),
			array('access',1),
        ); 
		$in = $this->validParam($rules);//入参处理	
		$access = M("access");
		$access->where("role_id=".$in['rid'])->delete();//清空当前角色所有权限
		$data=array();
		if(empty($in['access'])){
			
			$rel['code'] = '0';
			$rel['msg'] = '修改成功';
		}else{	
			foreach($in['access'] as $k=>$v){
				$data = array(
					'role_id'=>$in['rid'],
					'node_id'=>$v['node_id'],
					'level'=>$v['level'],
				); 
				$return = $access->add($data);
				
			}
			if($return){
				$rel['code'] = '0';
				$rel['msg'] = '修改成功';
			}else{
				$rel['code'] = '1';
				$rel['msg'] = '修改失败！';
			}
		}
		
		
		$this->ajaxReturn($rel,'JSON');
	}


	/** 
     * @desc 左侧菜单 
     * @param
     * @author 鲁翠霞
     * @final 2020-04-26
     */
	public function leftMenu(){

		//超级管理员登陆
		if(session(C('ADMIN_AUTH_KEY'))){
			$node = D('node')->where('level=2')->order('sort')->relation(true)->select();
		}else{
			//取出所有权限节点
			$node = D('node')->where('level=2')->order('sort')->relation(true)->select();
			//取出当前登陆用户所有模块权限（英文名称）和操作权限（id)
			$moudle = '';
			$node_id = '' ;
			$accessList = $_SESSION["_ACCESS_LIST"];	
			foreach($accessList as $key=>$value){
				foreach($value as $key1=>$value1){
					$moudle = $moudle . ',' .$key1;
					foreach($value1 as $key2=>$value2){
						$node_id = $node_id . ',' . $value2;
					}
				}
			}
		
		//   dump($moudle);
		//   dump($node_id);exit;
		// dump($node);
		//去除没有权限的节点
			foreach($node as $key=>$value){
				if(!in_array(strtoupper($value['name']),explode(',',$moudle))){
					unset($node[$key]);
				}else{
					//模块存在，比较里面的操作
					foreach($value['node'] as $key1=>$value1){
						if(!in_array($value1['id'],explode(',',$node_id))){
							unset($node[$key]['node'][$key1]);
						}
					}
				}
			}
		}
		//dump($node);
		$rel['code'] = '0';
		$rel['msg'] = '查询成功';
		$rel['data'] = $node;
		$this->ajaxReturn($rel,'JSON');
	}




	







}