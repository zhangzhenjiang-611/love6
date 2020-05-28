<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
class IntroduceController extends BaseController{
	function add(){
		$this->display();
	}
	function save(){
		$ob=M('Summarize');
		$re=$ob->data($_POST)->add();
		if($re){
			$this->success("添加成功",U("Admin/Introduce/add"));
		}else{
			$this->error("添加失败",U("Admin/Introduce/add"));
		}
	}
	function oper(){
		$summarize =M('Summarize');
		$arr=$summarize->select();
		$this->assign('arr',$arr);
		$this->display();
	}
	function change(){
		$id=$_GET['id'];
		$ob=M('Summarize');
		$re=$ob->where("id=$id")
		       ->delete();
		if($re){
			$this->success("删除成功",U("Admin/Introduce/add"));
		}else{
			$this->error("删除失败",U("Admin/Introduce/oper"));
		}
	}
	function update(){
		$id=$_GET['id'];
		$ob=M('Summarize');
		$arr=$ob->where("id=$id")->select();
		$this->assign('arr',$arr);
		$this->display();
	}
	function usave(){
		$id=$_POST['id'];
		$ob=M("Summarize");
		$re=$ob->where("id=$id")->data($_POST)->save();
		if($re){
			$this->success("修改成功",U("Admin/Introduce/oper"));
		}else{
			$this->error("修改失败",U("Admin/Introduce/oper"));
		}
	}
	
	
	
	
}