<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/31
 * Time: 17:30
 */
namespace Admin\Controller;
use Think\Controller;
class RbacController extends BaseController {
    //用户列表
    public function index() {
        $user = D('UserRelation')->field('password',true)->relation('role')->select();
        $this->assign('user',$user);
        $this->display();

    }

    //角色列表
    public function role() {
       $role = M('role')->select();
       $this->assign('role',$role);
       $this->display();

    }

    //节点列表
    public function node() {
        $node = M('node')->order('sort')->field('id,name,title,pid')->select();
        $node = nodeMerge($node);
        $this->assign('node',$node);
        $this->display();

    }

    //添加用户
    public function addUser() {
        $this->role = M('role')->select();
        $this->display();

    }
    //添加用户表单处理
    public function addUserHandle() {
        $user = array(
            'username' => I('username',''),
            'password' => I('password','','md5'),
            'logintime' => time(),
            'loginip' => get_client_ip()
        );
        $role = array();
        if($uid = M('user')->add($user)) {
            foreach($_POST['role_id'] as $v) {
                $role[] = array(
                    'role_id' =>$v,
                    'user_id' =>$uid
                );
            }
           $res =  M('role_user')->addAll($role);
            if($res) {
                $this->success('添加成功',U('index'));
            } else {
                $this->error('添加失败');
            }

        }
    }

    //添加角色
    public function addRole() {
        $this->display();

    }
    //添加角色表单处理
    public function addRoleHandle() {
       //dump($_POST);
        if(M('role')->add($_POST)) {
            $this->success('添加成功',U('role'));
        } else {
            $this->error('添加失败');
        }

    }

    //添加节点
    public function addNode() {
       // $pid = isset($_GET['pid']) ? $_GET['pid'] :0;
        $this->pid = I('pid',0,'intval');
        $this->level = I('level',1,'intval');
        switch ($this->level) {
            case 1:
                $this->type = '应用';
                break;
                    case 2:
                    $this->type = '控制器';
                    break;
                        case 3:
                        $this->type = '方法';
                        break;
        }
        $this->display();

    }

    //添加节点表单处理
    public function addNodeHandle() {
        if(M('node')->add($_POST)) {
            $this->success('添加成功',U('node'));
        } else {
            $this->error('添加失败');
        }

    }
    //配置权限
    public function access() {
        $rid = I('rid','','intval');
        $node = M('node')->order('sort')->field('id,name,title,pid')->select();
        $access = M('access')->where(array('role_id' => $rid))->getField('node_id',true);
        $node = nodeMerge($node,$access);
        $this->node = $node;
        $this->rid = $rid;
        $this->display();
    }
    //修改权限
    public function setAccess() {
        $rid = I('rid',0,'intval');
        $access = M('access');
        //清空原来的权限
        $access->where(array('role_id' => $rid))->delete();
        //组合新权限
        $data = array();
        foreach ($_POST['access'] as $v) {
            $tmp = explode('_',$v);
            $data[] = array(
                'role_id' =>$rid,
                'node_id' =>$tmp[0],
                'level' =>$tmp[1]
            );
        }
        //插入新权限
        if($access->addAll($data)) {
            $this->success('修改成功',U('role'));
        } else {
            $this->error('修改失败');
        }


    }

}