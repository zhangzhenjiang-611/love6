<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
class DoctorController extends Controller{
    function index(){
        $keywords = I("get.keywords");
        $keshi = M("doctor");
        $count = $keshi->where("name like '%".$keywords."%' or keshi like '%".$keywords."%'")->order(array('tid'=>'asc','zhichengpaixu'=>'desc','paixu'=>'desc'))->count();
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
        $res = $keshi->where("name like '%".$keywords."%' or keshi like '%".$keywords."%'")->order(array('tid'=>'asc','zhichengpaixu'=>'desc','paixu'=>'desc'))->limit($Page->firstRow,$Page->listRows)->select();
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
        //获取医生姓名
        $data['name'] = I("post.name");
        //获取医生职称
        $data['zhicheng'] = I("post.zhicheng");
        //医生职称排序
        if(I("post.zhicheng")=="主任医师"){
            $data['zhichengpaixu']=20;
        }elseif(I("post.zhicheng")=="副主任医师"){
            $data['zhichengpaixu']=15;
        }else{
            $data['zhichengpaixu']=0;
        }
        //获取医生排序
        $data['paixu'] = I("post.paixu");
        //获取医生专业特长
        $data['techang'] = I("post.techang");
        //获取医生介绍
        $data['title'] = I("post.title");
        // 获取科室名称
        $res = M("type")->where('id='.$data['tid'])->find();
        $data['keshi']= $res['name'];

        //上传图片操作
        $upload = new \Think\Upload();// 实例化上传类    
        $upload->maxSize  = 3145728 ;// 设置附件上传大小    
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置图片上传类型    
        $upload->rootPath = './Public/admin/doctorphoto/'; // 设置图片上传目录    
        $upload->autoSub = false; //不使用子目录保存上传文件   
        // 上传文件     
        $info = $upload->upload();    
        if(!$info) {
            // 上传错误提示错误信息       
            $this->error($upload->getError());    
        }else{
          
            // $data['photo'] = "./Public/admin/doctorphoto/".$info['photo']['savename'];
            // echo '<img src="'.__ROOT__.'/Public/admin/doctorphoto/'.$info['photo']['savename'].'" alt="" width="200px">'; exit;
            $data['photo'] = __ROOT__.'/Public/admin/doctorphoto/'.$info['photo']['savename'];
        }
        $name = $data['name'];
        $doctor = M("doctor");
        $count = $doctor->where("name='$name'")->count();
        if($count>=1){
            $this->error("医生已经存在,添加失败");
        }else{
            if($doctor->add($data)){
                $this->success("添加成功",U("Doctor/index"));
            }else{
                $this->error("添加失败",U("Doctor/index"));
            }
        }
       
    }
     public function delete(){
    	$id = I("get.id");
    	$doctor = M("doctor");
    	// 获取图片地址
    	$row = $doctor->where("id=$id")->find();
    	$photo = $row['photo'];
        $photo ='./'.strstr($photo,'Public');
        //删除照片
    	if(file_exists($photo)){
            unlink($photo);
        }
        //删除医生记录
    	$res = $doctor->where("id=$id")->delete();
    	if($res){
			$this->success("删除成功",U("Doctor/index"));
		}else{
			$this->error("删除失败",U("Doctor/index"));
		}
    	
    }
    public function edit(){
    	$id = I("get.id");
    	$res0 = M("type")->where("pid!=0")->order("path")->select();
        $this->assign('list0',$res0);
    	$keshi = M("doctor");
    	$res = $keshi->where("id=$id")->find();
    	$this->assign('list',$res);
		$this->display();
    	
    }
     public function update(){
     	$id = I("post.id");
    	  //获取科室类别
        $data['tid'] = I("post.type_id");
        //获取医生姓名
        $data['name'] = I("post.name");
        //获取医生职称
        $data['zhicheng'] = I("post.zhicheng");
        //医生职称排序
        if(I("post.zhicheng")=="主任医师"){
            $data['zhichengpaixu']=20;
        }elseif(I("post.zhicheng")=="副主任医师"){
            $data['zhichengpaixu']=15;
        }else{
            $data['zhichengpaixu']=0;
        }
        //获取医生排序
        $data['paixu'] = I("post.paixu");
        //获取医生专业特长
        $data['techang'] = I("post.techang");
        //获取医生介绍
        $data['title'] = I("post.title");
        //获取原图片路径
        $oldphoto = I("post.oldphoto");
        // $data['photo'] = $_FILES['photo']['error'];
        // 获取科室名称
        $res = M("type")->where('id='.$data['tid'])->find();
        $data['keshi']= $res['name'];
        // dump($data);exit;
        //上传图片操作
        $upload = new \Think\Upload();// 实例化上传类    
        $upload->maxSize  = 3145728 ;// 设置附件上传大小    
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置图片上传类型    
        $upload->rootPath = './Public/admin/doctorphoto/'; // 设置图片上传目录    
        $upload->autoSub = false; //不使用子目录保存上传文件   
        // 上传文件     
        $info = $upload->upload();   
        if(!$info){
            
            if($upload->getError()=="没有文件被上传！"){
                //没有上传图片时保存原图片路径
               $data['photo'] = $oldphoto;
            }else{
                //上传错误提示错误信息
               $this->error($upload->getError());
            }
        }else{
          
            // $data['photo'] = "./Public/admin/doctorphoto/".$info['photo']['savename'];
            // echo '<img src="'.__ROOT__.'/Public/admin/doctorphoto/'.$info['photo']['savename'].'" alt="" width="200px">'; exit;
            $data['photo'] = __ROOT__.'/Public/admin/doctorphoto/'.$info['photo']['savename'];
        }
        $doctor = M("doctor");
		$res = $doctor->where("id=".$id)->data($data)->save();
        // echo $keshi->_SQL();exit;
		if($res){
			$this->success("修改成功",U("Doctor/index"));
		}else{
			$this->error("修改失败",U("Doctor/index"));
		}
    	
    }
}