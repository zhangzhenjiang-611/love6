<?php
namespace ZiZhu\Controller;
use Think\Controller;
class MdController extends Controller {
	public function index(){
    	$id = I("get.id");
	    $this->assign("id",$id);
	    $this->display();
  	}
	public function add(){
		// var_dump(count($_POST));exit;
		if(count($_POST)<8){
			$this->error(" ",U("Chaxun/Md/index",array('id'=>12)));
		}
	  	if(count($_POST['kanzhong'])>2){
	  		$this->error("",U("Chaxun/Md/index",array('id'=>12)));
	  	}else{
	  		$_POST['kanzhong'] = json_encode($_POST['kanzhong']);
	  		$res = M("diaocha")->add($_POST);
	  		$this->success("",U("Chaxun/Md/index",array('id'=>12)));
	  	}
  	}

	public function fanye()
  	{
  		$res = M("diaocha")->select();
  		for ($i=0; $i <count($res); $i++) {
  			$res[$i]['kanzhong']=implode(json_decode($res[$i]['kanzhong']),",");
  		}
  		// print_r($res);exit;

  	}

}