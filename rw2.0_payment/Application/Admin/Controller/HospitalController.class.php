<?php
namespace Admin\Controller;
use Think\Controller;
class HospitalController extends Controller{
    function index(){
        $hospital_detail = M("hospital_detail");
        $data = $hospital_detail->order("id desc")->limit(1)->find();
        $this->assign("data",$data);
        $this->display();
    }
    public function add(){
         $this->display();
    }
    public function insert(){
        // print_r($_POST);exit;
        // 获取医院介绍信息
        $data = $_POST;
        $hospital_detail = M("hospital_detail");
        $count = $hospital_detail->count();
        if($count>=1){
            $this->error("添加失败",U("Hospital/index"));
        }else{
            if($hospital_detail->add($data)){
                $this->success("添加成功",U("Hospital/index"));
            }else{
                $this->error("添加失败",U("Hospital/index"));
            }
        }
       
    }
     public function delete(){
    	$id = I("get.id");
    	$hospital_detail = M("hospital_detail");
    	$result = $hospital_detail->where("id=$id")->delete();
    	if($result){
			$this->success("删除成功",U("Hospital/index"));
		}else{
			$this->error("删除失败",U("Hospital/index"));
		}
    	
    }
    public function edit(){
    	$id = I("get.id");
    	$hospital_detail = M("hospital_detail");
    	$row = $hospital_detail->where("id=$id")->find();
    	$this->assign('row',$row);
		$this->display();
    	
    }
     public function update(){
     	$id=$_POST['id'];
    	$hospital_detail = M("hospital_detail");
		$result=$hospital_detail->where("id=$id")->data($_POST)->save();
		if($result){
			$this->success("修改成功",U("Hospital/index"));
		}else{
			$this->error("修改失败",U("Hospital/index"));
		}
    	
    }
}