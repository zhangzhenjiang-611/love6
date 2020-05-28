<?php
namespace Admin\Controller;
use \Admin\Controller\BaseController;
class ProductController extends BaseController{
	//显示添加产品表单
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
		//保存产品信息
		$ob=M('Product');
        //$_POST['pubtime'] = time();
		$re=$ob->data($_POST)->add();
		if($re){
			//保存图片信息
			$imageOb=M("Productimage");
			if($arr){
				foreach($arr as $v){
					$imagename=$v['savename'];
					$imageRe=$imageOb->data(array('pid'=>$re,'imagename'=>$imagename))->add();
				}
				$this->success("保存成功",U("Admin/Product/oper"));
			}else{
				$this->error('图片上传失败，产品信息保存成功',U('Admin/Product/oper'));
			}
		}else{
			$this->error('产品保存失败',U('Admin/Product/add'));
		}
	}
	function oper(){
	    $productOb=M('Product');
        $num = $productOb->count();
        $pageSize = 5;
        $page=new \Think\Page($num,$pageSize);
        $page->setConfig('first','首页');
        $page->setConfig('last','尾页');
        $page->lastSuffix=false;
        $page->rollPage = 6;
        $start=$page->firstRow;
        $arr=$productOb
            ->alias('p')
            ->limit("$start,$pageSize")
            ->order("p.id desc")
            ->select();
        //每个产品获取一次图片
        $imageOb=M("Productimage");
        //$type = M("type");
        foreach($arr as $k=>$v){
            $pid=$v['id'];
            $tid=$v['typeid'];
            $imageArr=$imageOb->where("pid=$pid")->find();
            //$tnameArr=$type->where("id=$tid")->find();
            $tname=$tnameArr['tname'];
            if($imageArr){
                $imagename=$imageArr['imagename'];
            }else{
                $imagename="wutu.jpg";
            }
            //把分类名称放进$arr中
            $arr[$k]['tname']=$tname;
            //把图片名称放到$arr中
            $arr[$k]['imagename']=$imagename;
        }
        $pageStr=$page->show();
        $this->assign('arr',$arr);
        //var_dump($arr);exit();
        $this->assign('pageStr',$pageStr);
        $this->display();
    }
    function change(){
        $id=$_GET['id'];
        $product=M("Product");
        $product->where("id=$id")
                ->delete();
        header("location:".$_SERVER['HTTP_REFERER']);
    }
    function update(){
        //$optionStr=getTypeP();
        $id=$_GET['id'];
        //根据产品id，获取产品信息
        $product=M('Product');
        $pArr=$product->where("id=$id")
            ->find();
        //根据产品id，获取图片信息
        $productimage=M("Productimage");
        $imageArr=$productimage->where("pid=$id")
            ->select();
        /*$type = M("type");
        $arr=$type->select();*/
        $this->assign('arr',$arr);
        $this->assign('pArr',$pArr);
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
        $imageOb=M("Productimage");
        $re=$imageOb->where("id=$id")->delete();
        if($re){
            echo "1";
        }else{
            echo "0";
        }
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
            $imageOb=M("Productimage");
            foreach($uploadRe as $v){
                $imagename=$v['savename'];
                $imageOb->data(array('pid'=>$id,'imagename'=>$imagename))->add();
            }
        }
        //保存产品信息
        $product=M('Product');
        $re=$product->where("id=$id")->data($_POST)->save();
        if($re===false){
            $this->error("修改失败",U("Admin/Product/oper"));
        }else{
            $this->success("修改成功",U("Admin/Product/oper"));
        }
    }
    function search(){
        $search =  $_GET['search'];
        $productOb=M('Product');
        $num = $productOb->where("state<9 and title like '%$search%'")->count();
        echo $productOb->getLastSql();
        exit;
        $pageSize = 5;
        $page=new \Think\Page($num,$pageSize);
        $page->setConfig('first','首页');
        $page->setConfig('last','尾页');
        $page->lastSuffix=false;
        $page->rollPage = 6;
        $start=$page->firstRow;
        $arr=$productOb
            ->alias('p')
            ->where("p.state<9 and and p.title like '%$search%'")
            ->limit("$start,$pageSize")
            ->order("p.id desc")
            ->select();
        //每个产品获取一次图片
        $imageOb=M("Productimage");
        foreach($arr as $k=>$v){
            $pid=$v['id'];
            $imageArr=$imageOb->where("pid=$pid")->find();
            if($imageArr){
                $imagename=$imageArr['imagename'];
            }else{
                $imagename="wutu.jpg";
            }
            //把图片名称放到$arr中
            $arr[$k]['imagename']=$imagename;
        }
        $pageStr=$page->show();
        $this->assign('arr',$arr);
        var_dump($arr);exit;
        $this->assign('pageStr',$pageStr);
        $this->display();
    }
}





















