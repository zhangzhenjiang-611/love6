<?php
namespace Admin\Controller;
use Think\Controller;
class TypeController extends Controller{
   
    public function add(){
        //查询所有分类
        $res = self::getCates();
        $this->assign("list",$res);
        $this->display();
    }
    //获取分类数据
    public static function getCates(){
        $res = M('type')->query("select * ,concat(path,',',id) as paths from type order by paths");
        // echo "<pre>";
        // var_dump($res);exit;
        foreach($res as &$v){
            // echo $v['name'].'---'.count(explode(',',$v['path'])).'<br>';
            $num = count(explode(',',$v['path']))-1;
            $v['name']=str_repeat('|---',$num).$v['name'];
        }
        // exit;
        return $res;
    }
    //执行添加
    public function insert(){
        $id = I("post.pid");
        if($id==0){
            //添加顶级父类
            $data['name'] = I("post.name");
            $data['pid']=0;
            $data['path']='0';   
        }else{
            //添加id等于I("post.pid")的子类
            $data['name']=I("post.name");
            $data['pid']=I("post.pid");
            // echo $data['pid'];exit;
            //查询父类的信息path 连接 父类的id 形成子类的path
            $res = M('type')->where('id='.$id)->find();
            $data['path']=$res['path'].','.$res['id'];
        }

        //执行添加
        $res = M('type')->data($data)->add();
        if($res){
            //添加成功
           $this->success("添加成功",U("Type/add"));
        }else{
            //添加失败
           $this->error("添加失败");
        }
       
    }

    // 显示分类列表
    public function index(){
        //查询所有的类别数据
        $res = self::getCates();
        $this->assign('list',$res);
        $this->display();
    }
    
    // 删除操作

    public function delete($id){
        //如果该类有子类不能删除
        $res = M('type')->where('pid='.$id)->count();
        // echo M('type')->_SQL();exit;
        if($res>0){
            $this->error("该类有子类不能删除");
        }else{
            //执行删除
            $res = M('type')->where('id='.$id)->delete();
            if($res){
                //添加成功
                $this->success("删除成功",U("Type/index"));
            }else{
               $this->error("删除失败");
            }
        }
   }
    

   // 加载修改表单
    public function edit(){
        $id = I("get.id");
        $data = M('type')->where('id='.$id)->find();
        // echo M('type')->_SQL();exit;
        // var_dump($data);exit;
        $this->assign('vo',$data);
        $this->assign('list',self::getCates());
        $this->assign('id',$data['pid']);
        $this->display();
   }
    // 执行修改
    public function update(){
        //获取修改的类别的id
        $id = I("post.id");
        $data['name'] = I("post.name");
        $res = M('type')->where('id='.$id)->data($data)->save();
        if($res){
            $this->success("修改成功",U("Type/index"));
        }else{
            $this->error("修改失败",U("Type/index"));
        }
   }
}