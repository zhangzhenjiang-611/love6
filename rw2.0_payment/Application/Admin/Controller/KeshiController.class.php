<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
class KeshiController extends Controller{
    function index(){
        $keywords = I("get.keywords");
        $keshi = M("keshi");
        $count = $keshi->where("name like '%".$keywords."%'")->count();
        $Page = new Page($count,C("PAGE_SIZE"));
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        //分页跳转的时候保证查询条件
        $map['keywords'] = $keywords;
        foreach($map as $key=>$val) {
            $Page->parameter[$key] = $val;
        }
        // var_dump($Page->parameter);exit;
        //进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $res = $keshi->where("name like '%".$keywords."%'")->limit($Page->firstRow,$Page->listRows)->select();
        // echo $keshi->_SQL();exit;

        // 分页显示输出
        $show = $Page->show();
        // 赋值分页输出
        $this->assign('page',$show);
        $this->assign("list",$res);
        $this->assign("count",$count);
        $this->assign("keywords",$keywords);
        $this->display();
    }
    //加载添加页面
    public function add(){
        $res = M("type")->where("pid!=0")->order("path")->select();
        $this->assign('list',$res);
        $this->display();
    }
    //执行添加
    public function insert(){
        //获取科室类别
        $data['tid'] = I("post.type_id");
        // 获取科室名称
        $res = M("type")->where('id='.$data['tid'])->find();
        $data['name']= $res['name'];
        //获取科室介绍
        $data['content'] = I("post.title");
        $keshi = M("keshi");
        $count = $keshi->where('name='.$data['name'])->count();
        if($count>=1){
            $this->error("科室介绍已经存在,添加失败");
        }else{
            if($keshi->add($data)){
                $this->success("添加成功",U("Keshi/index"));
            }else{
                $this->error("添加失败",U("Keshi/index"));
            }
        }
       
    }
     public function delete(){
    	$id = I("get.id");
    	$keshi = M("keshi");
    	$res = $keshi->where("id=$id")->delete();
    	if($res){
			$this->success("删除成功",U("Keshi/index"));
		}else{
			$this->error("删除失败",U("Keshi/index"));
		}
    	
    }
    public function edit(){
    	$id = I("get.id");
    	$keshi = M("keshi");
    	$res = $keshi->where("id=$id")->find();
    	$this->assign('list',$res);
		$this->display();
    	
    }
     public function update(){
     	$id = I("post.id");
    	$keshi = M("keshi");
        $data['content'] = I("post.title");
        // echo $data['content'];exit;
		$res = $keshi->where("id=".$id)->data($data)->save();
        // echo $keshi->_SQL();exit;
		if($res){
			$this->success("修改成功",U("Keshi/index"));
		}else{
			$this->error("修改失败",U("Keshi/index"));
		}
    	
    }
}