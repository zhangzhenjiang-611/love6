<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
class ShiftController extends BaseController{
    function add(){
        $this->display();
    }
    function save(){
		//上传图片
		$upload=new \Think\Upload();
		$upload->mimes=array('image/png','image/jpeg','image/gif','image/pjpeg');
		$upload->exts=array('gif','png','jpg');
		$upload->autoSub=false;
		$upload->rootPath="./Public/";
		$upload->savePath="upload/";
		$upload->saveName=array('uniqid',array(mt_rand(1000,9999),true));
		$arr=$upload->upload();
		if($arr){
			//保存图片信息
			$imageOb=M("Shiftimage");
			if($arr){
				foreach($arr as $v){
					$imagename=$v['savename'];
					$imageRe=$imageOb->data(array('pid'=>$re,'imagename'=>$imagename))->add();
				}
				$this->success("保存成功",U("Admin/Shift/oper"));
			}else{
				$this->error('图片上传失败，产品信息保存成功',U('Admin/Product/oper'));
			}
		}else{
			$this->error('产品保存失败',U('Admin/Product/add'));
		}
	}
	function oper(){
		$Shiftimage =M('Shiftimage');
		$arr=$Shiftimage->select();
		$this->assign('arr',$arr);
		$this->display();
	}
	function update(){
        $productimage=M("Shiftimage");
        $imageArr=$productimage->select();
        $this->assign('imageArr',$imageArr);
        //$this->assign('optionStr',$optionStr);
        $this->display();

    }
    function delimage(){
        $id=$_GET['id'];
        $name=$_GET['name'];
        //删除图片文件
        @unlink("./Public/upload/".$name);
        //删除图片记录
        $imageOb=M("Shiftimage");
        $re=$imageOb->where("id=$id")->delete();
        if($re){
            echo "1";
        }else{
            echo "0";
        }
    }
    function change(){
        $id=$_GET['id'];
        $product=M("Shiftimage");
        $product->where("id=$id")
                ->delete();
        header("location:".$_SERVER['HTTP_REFERER']);
    }
    function usave(){
        $id=$_POST['id'];
        //上传图片
        $upload=new \Think\Upload();
        $upload->exts=array('gif','png','jpg');
        $upload->mimes=array("image/jpeg",'image/gif','image/png');
        $upload->rootPath="./Public/";
        $upload->savePath="upload/";
        $upload->saveName=array('uniqid',array(mt_rand(1000,9999),true));
        $upload->autoSub=false;
        $uploadRe=$upload->upload();

        //保存图片
        if(is_array($uploadRe)){
            $imageOb=M("Shiftimage");
            foreach($uploadRe as $v){
                $imagename=$v['savename'];
                $imageOb->data(array('pid'=>$id,'imagename'=>$imagename))->add();
            }
        }
        if(is_array($uploadRe)){
            $this->success("修改成功",U("Admin/Shift/oper"));
        }else{
            $this->error("修改失败",U("Admin/Shift/oper"));
        }
    }
}